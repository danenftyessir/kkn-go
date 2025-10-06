<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
     * proses login
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

        // rate limiting (max 5 attempts per menit)
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => "terlalu banyak percobaan login. coba lagi dalam {$seconds} detik.",
            ]);
        }

        // coba login dengan email atau username
        $credentials = $this->getCredentials($request);
        $remember = $request->filled('remember');

        // log untuk debugging
        Log::info('Login attempt', [
            'input' => $request->input('email'),
            'credentials_field' => array_keys($credentials)[0],
        ]);

        if (Auth::attempt($credentials, $remember)) {
            // clear rate limiter
            RateLimiter::clear($throttleKey);
            
            // regenerate session
            $request->session()->regenerate();

            // log sukses
            Log::info('Login successful', [
                'user_id' => Auth::id(),
                'user_type' => Auth::user()->user_type,
            ]);

            // redirect berdasarkan user type
            return $this->authenticated($request, Auth::user());
        }

        // increment rate limiter
        RateLimiter::hit($throttleKey, 60);

        // log gagal
        Log::warning('Login failed', [
            'input' => $request->input('email'),
        ]);

        // login gagal
        throw ValidationException::withMessages([
            'email' => 'email/username atau password salah.',
        ]);
    }

    /**
     * get credentials untuk login (email atau username)
     * PERBAIKAN: hapus pengecekan is_active dari credentials
     */
    protected function getCredentials(Request $request)
    {
        $login = $request->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // PERBAIKAN: jangan tambahkan is_active di credentials
        // karena itu akan menyebabkan login selalu gagal jika user belum ada di seeder
        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * redirect setelah login berhasil
     */
    protected function authenticated(Request $request, $user)
    {
        // PERBAIKAN: cek is_active setelah authenticated, bukan di credentials
        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'akun anda telah dinonaktifkan. hubungi admin untuk informasi lebih lanjut.']);
        }

        // cek apakah email sudah diverifikasi
        if (!$user->email_verified_at) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'email anda belum diverifikasi. silakan cek email untuk verifikasi.']);
        }

        // redirect berdasarkan user type
        switch ($user->user_type) {
            case 'student':
                return redirect()->intended(route('student.dashboard'));
                
            case 'institution':
                // cek apakah instansi sudah diverifikasi
                if ($user->institution && !$user->institution->is_verified) {
                    return redirect()
                        ->route('institution.dashboard')
                        ->with('warning', 'akun instansi anda masih menunggu verifikasi admin.');
                }
                return redirect()->intended(route('institution.dashboard'));
                
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
                
            default:
                Auth::logout();
                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'tipe user tidak valid.']);
        }
    }

    /**
     * logout user
     */
    public function logout(Request $request)
    {
        $userType = Auth::user()->user_type ?? null;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', ['user_type' => $userType]);

        return redirect()
            ->route('home')
            ->with('success', 'anda berhasil logout');
    }
}