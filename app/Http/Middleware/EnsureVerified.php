<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EnsureVerified
{
    /**
     * handle request untuk memastikan user sudah terverifikasi
     * khusus untuk instansi yang perlu verifikasi admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // hanya cek verifikasi untuk instansi
        if ($user->isInstitution()) {
            if (!$user->is_verified) {
                Log::warning('EnsureVerified: Institution not verified', [
                    'user_id' => $user->id,
                    'institution_id' => $user->institution?->id,
                ]);
                
                // redirect ke dashboard dengan pesan
                return redirect()->route('institution.dashboard')
                    ->with('warning', 'Akun instansi Anda sedang dalam proses verifikasi. Beberapa fitur mungkin terbatas.');
            }
        }

        return $next($request);
    }
}