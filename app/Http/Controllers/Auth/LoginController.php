<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * proses login dengan session persistence fix
     */
    public function login(Request $request)
    {
        // validasi input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'email atau username wajib diisi',
            'password.required' => 'password wajib diisi',
        ]);

        // rate limiting untuk mencegah brute force
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "terlalu banyak percobaan login. coba lagi dalam {$seconds} detik.",
            ]);
        }

        $loginInput = $request->input('email');
        $passwordInput = $request->input('password');
        
        Log::info('=== LOGIN ATTEMPT ===', [
            'input' => $loginInput,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // tentukan apakah input adalah email atau username
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // cari user berdasarkan email atau username
        $user = User::where($field, $loginInput)->first();
        
        if (!$user) {
            Log::warning('User not found', ['input' => $loginInput, 'field' => $field]);
            RateLimiter::hit($throttleKey, 60);
            
            throw ValidationException::withMessages([
                'email' => 'email/username atau password salah.',
            ]);
        }

        Log::info('User found', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'is_active' => $user->is_active,
        ]);

        // verifikasi password
        if (!Hash::check($passwordInput, $user->password)) {
            Log::warning('Incorrect password', ['user_id' => $user->id]);
            RateLimiter::hit($throttleKey, 60);
            
            throw ValidationException::withMessages([
                'email' => 'email/username atau password salah.',
            ]);
        }

        Log::info('Password correct, attempting login', ['user_id' => $user->id]);

        // siapkan credentials untuk Auth::attempt
        $credentials = [
            $field => $loginInput,
            'password' => $passwordInput,
        ];
        
        $remember = $request->filled('remember');

        Log::info('Session config before login', [
            'driver' => config('session.driver'),
            'cookie' => config('session.cookie'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'same_site' => config('session.same_site'),
            'http_only' => config('session.http_only'),
        ]);

        // CRITICAL: gunakan Auth::attempt dengan proper session handling
        if (Auth::attempt($credentials, $remember)) {
            // clear rate limiter karena login berhasil
            RateLimiter::clear($throttleKey);
            
            Log::info('Auth::attempt SUCCESS', [
                'user_id' => Auth::id(),
                'auth_check' => Auth::check(),
            ]);
            
            // PENTING: regenerate session untuk mencegah session fixation
            $request->session()->regenerate();
            
            // CRITICAL FIX: pastikan session tersimpan ke database
            // ini penting untuk environment seperti Railway/production
            $request->session()->save();
            
            // tunggu sebentar untuk memastikan session tersimpan
            usleep(100000); // 100ms
            
            $sessionId = session()->getId();
            
            Log::info('Session after regenerate and save', [
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
            ]);
            
            // verifikasi session tersimpan di database (untuk driver database)
            if (config('session.driver') === 'database') {
                try {
                    $sessionExists = \DB::table('sessions')
                        ->where('id', $sessionId)
                        ->exists();
                    
                    $sessionCount = \DB::table('sessions')
                        ->where('user_id', Auth::id())
                        ->count();
                    
                    Log::info('Session verification', [
                        'session_exists' => $sessionExists,
                        'user_sessions_count' => $sessionCount,
                    ]);
                    
                    if (!$sessionExists) {
                        Log::error('Session not found in database after save!', [
                            'session_id' => $sessionId,
                            'user_id' => Auth::id(),
                        ]);
                        
                        // fallback: coba save lagi
                        $request->session()->save();
                        usleep(50000); // 50ms
                    }
                } catch (\Exception $e) {
                    Log::error('Error verifying session', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
            
            // redirect ke halaman yang sesuai dengan user type
            return $this->authenticated($request, Auth::user());
        }

        // jika Auth::attempt gagal meskipun password benar
        // ini biasanya terjadi karena masalah konfigurasi
        Log::error('Auth::attempt FAILED despite correct password', [
            'user_id' => $user->id,
            'field' => $field,
            'remember' => $remember,
        ]);

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => 'terjadi kesalahan saat login. silakan coba lagi.',
        ]);
    }

    /**
     * redirect setelah login berhasil berdasarkan user type
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info('=== AUTHENTICATED METHOD ===', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'auth_check' => Auth::check(),
            'session_id' => session()->getId(),
        ]);

        // TEMPORARY: skip email verification untuk debugging
        // hapus comment ini setelah masalah session selesai
        /*
        if (isset($user->is_active) && !$user->is_active) {
            Log::warning('Account inactive', ['user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'akun anda telah dinonaktifkan.']);
        }

        if (!$user->email_verified_at) {
            Log::warning('Email not verified', ['user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'email anda belum diverifikasi.']);
        }
        */

        // tentukan route redirect berdasarkan user type
        $route = match($user->user_type) {
            'student' => route('student.dashboard'),
            'institution' => route('institution.dashboard'),
            default => function() use ($user) {
                Log::error('Invalid user type', [
                    'user_id' => $user->id,
                    'user_type' => $user->user_type,
                ]);
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'tipe user tidak valid.']);
            }
        };
        
        // jika route adalah closure (error case), return langsung
        if ($route instanceof \Closure) {
            return $route();
        }

        Log::info('Redirecting to dashboard', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'route' => $route,
        ]);
        
        // CRITICAL: save session sekali lagi sebelum redirect
        // untuk memastikan session benar-benar tersimpan
        $request->session()->save();

        // gunakan intended untuk redirect ke halaman yang diminta sebelumnya
        // atau ke dashboard jika tidak ada
        return redirect()->intended($route)
            ->with('success', 'selamat datang, ' . $user->name . '!');
    }

    /**
     * logout user
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userType = Auth::user()->user_type ?? null;

        Log::info('User logging out', [
            'user_id' => $userId,
            'user_type' => $userType,
            'session_id' => session()->getId(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'anda berhasil logout');
    }
}