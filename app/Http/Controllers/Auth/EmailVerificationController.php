<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    /**
     * tampilkan halaman notice untuk verifikasi email
     */
    public function notice()
    {
        // jika sudah terverifikasi, redirect ke dashboard
        if (auth()->user()->email_verified_at) {
            return redirect()->route('home');
        }
        
        return view('auth.verify-email');
    }

    /**
     * verifikasi email dengan token
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // cek apakah hash valid
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()
                ->route('verification.notice')
                ->withErrors(['email' => 'link verifikasi tidak valid']);
        }

        // cek apakah sudah terverifikasi
        if ($user->email_verified_at) {
            return redirect()
                ->route('home')
                ->with('info', 'email sudah terverifikasi sebelumnya');
        }

        // tandai email sebagai terverifikasi
        $user->update([
            'email_verified_at' => Carbon::now(),
            'email_verification_token' => null
        ]);

        // kirim notifikasi sukses
        // TODO: bisa ditambahkan email notification atau event

        return redirect()
            ->route('home')
            ->with('success', 'email berhasil diverifikasi! selamat bergabung di KKN-GO');
    }

    /**
     * kirim ulang email verifikasi
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        // cek apakah sudah terverifikasi
        if ($user->email_verified_at) {
            return back()->with('info', 'email sudah terverifikasi');
        }

        // cek rate limiting (max 1 request per menit)
        $lastSent = session('verification_last_sent');
        if ($lastSent && Carbon::parse($lastSent)->addMinute()->isFuture()) {
            $seconds = Carbon::parse($lastSent)->addMinute()->diffInSeconds(now());
            return back()->withErrors([
                'email' => "tunggu {$seconds} detik sebelum mengirim ulang"
            ]);
        }

        // generate token baru
        $token = sha1($user->email . now());
        $user->update(['email_verification_token' => $token]);

        // TODO: kirim email verifikasi
        // Mail::to($user->email)->send(new VerifyEmailMail($user, $token));

        // simpan timestamp
        session(['verification_last_sent' => now()]);

        return back()->with('success', 'email verifikasi berhasil dikirim ulang');
    }
}