<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
     * DENGAN DEBUG CODE UNTUK MENDETEKSI MASALAH
     */
    public function login(Request $request)
    {
        // 1. validasi input standar laravel
        $this->validateLogin($request);

        // --- LANGKAH DEBUGGING DIMULAI DI SINI ---
        $credentials = $this->credentials($request);
        $loginInput = $credentials['email']; // bisa email atau username
        $password = $credentials['password'];

        // 2. coba cari user berdasarkan email atau username yang diinput
        $user = User::where('email', $loginInput)
                    ->orWhere('username', $loginInput)
                    ->first();

        // 3. jika user TIDAK ADA di database, hentikan dan beri tahu kita
        if (!$user) {
            dd([
                'STATUS' => 'DEBUG: PENGGUNA TIDAK DITEMUKAN',
                'pesan' => 'Pengguna dengan email/username "' . $loginInput . '" TIDAK DITEMUKAN di database.',
                'input_yang_dicari' => $loginInput,
                'saran' => 'Cek apakah seeder sudah dijalankan di Railway, atau cek langsung di Supabase apakah data user ada.',
                'total_users_di_database' => User::count(),
                'sample_users' => User::limit(5)->get(['id', 'email', 'username', 'user_type']),
            ]);
        }

        // 4. jika user ADA, kita cek apakah passwordnya cocok
        if (Hash::check($password, $user->password)) {
            // jika password COCOK, hentikan dan beri tahu kita
            dd([
                'STATUS' => 'DEBUG: PASSWORD BENAR!',
                'pesan' => 'Pengguna ditemukan dan password cocok. Masalah ada pada proses setelah ini (sesi/redirect/middleware).',
                'user_ditemukan' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'user_type' => $user->user_type,
                    'is_active' => $user->is_active,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'langkah_selanjutnya' => 'Masalahnya bukan di kredensial. Cek konfigurasi SESSION, COOKIE, atau MIDDLEWARE.',
            ]);
        } else {
            // jika password SALAH, hentikan dan beri tahu kita
            dd([
                'STATUS' => 'DEBUG: PASSWORD SALAH',
                'pesan' => 'Pengguna ditemukan, tetapi password yang Anda masukkan tidak cocok dengan yang ada di database.',
                'detail' => [
                    'email_username_yang_dicari' => $loginInput,
                    'password_yang_dimasukkan' => '****** (disembunyikan untuk keamanan)',
                    'password_hash_di_database' => $user->password,
                ],
                'user_ditemukan_di_db' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                ],
                'saran' => 'Kemungkinan: (1) Password di seeder berbeda, (2) Hashing algorithm berbeda antara lokal dan Railway, (3) Data di Supabase tidak sesuai dengan seeder.',
            ]);
        }
    }

    /**
     * validasi login input
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'email atau username wajib diisi',
            'password.required' => 'password wajib diisi',
        ]);
    }

    /**
     * get credentials dari request
     */
    protected function credentials(Request $request)
    {
        $loginInput = $request->input('email');
        
        // tentukan apakah input adalah email atau username
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        return [
            'email' => $loginInput, // kita pakai key 'email' tapi valuenya bisa username juga
            'password' => $request->input('password'),
        ];
    }

    /**
     * redirect setelah login berhasil
     * NOTE: method ini tidak akan dieksekusi karena kita pakai dd() di atas
     * tapi tetap kita keep untuk nanti setelah debug selesai
     */
    protected function authenticated(Request $request, $user)
    {
        // cek is_active setelah authenticated, bukan di credentials
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