<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * handle incoming request untuk cek user type
     * 
     * middleware ini memastikan user yang mengakses route
     * sesuai dengan tipe user yang diizinkan
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        // log request info untuk debugging
        Log::info('CheckUserType middleware', [
            'path' => $request->path(),
            'method' => $request->method(),
            'required_type' => $userType,
            'session_id' => session()->getId(),
            'has_session' => session()->has('_token'),
        ]);
        
        // cek apakah user sudah login
        if (!Auth::check()) {
            Log::warning('User not authenticated in CheckUserType', [
                'path' => $request->path(),
                'required_type' => $userType,
                'session_id' => session()->getId(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()->route('login')
                ->with('error', 'silakan login terlebih dahulu')
                ->with('intended', $request->fullUrl());
        }
        
        $user = Auth::user();
        
        Log::info('User authenticated in CheckUserType', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'required_type' => $userType,
            'match' => $user->user_type === $userType,
        ]);

        // cek apakah tipe user sesuai
        if ($user->user_type !== $userType) {
            Log::warning('User type mismatch', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'required_type' => $userType,
                'path' => $request->path(),
            ]);
            
            // redirect ke dashboard sesuai role mereka
            $redirectRoute = match($user->user_type) {
                'student' => 'student.dashboard',
                'institution' => 'institution.dashboard',
                'admin' => 'admin.dashboard',
                default => 'home',
            };

            return redirect()->route($redirectRoute)
                ->with('error', 'anda tidak memiliki akses ke halaman ini');
        }

        // user type sesuai, lanjutkan request
        Log::info('CheckUserType passed', [
            'user_id' => $user->id,
            'path' => $request->path(),
        ]);
        
        return $next($request);
    }
}