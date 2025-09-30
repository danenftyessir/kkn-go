<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

        if (Auth::attempt($credentials, $remember)) {
            // clear rate limiter
            RateLimiter::clear($throttleKey);
            
            // regenerate session
            $request->session()->regenerate();

            // redirect berdasarkan user type
            return $this->authenticated($request, Auth::user());
        }

        // increment rate limiter
        RateLimiter::hit($throttleKey, 60);

        // login gagal
        throw ValidationException::withMessages([
            'email' => 'email/username atau password salah.',
        ]);
    }

    /**
     * get credentials untuk login (email atau username)
     */
    protected function getCredentials(Request $request)
    {
        $login = $request->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        return [
            $field => $login,
            'password' => $request->input('password'),
            'is_active' => true, // hanya user aktif yang bisa login
        ];
    }

    /**
     * redirect setelah login berhasil
     */
    protected function authenticated(Request $request, $user)
    {
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
                if (!$user->institution->is_verified) {
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
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'anda berhasil logout');
    }
}