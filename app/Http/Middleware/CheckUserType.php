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
     * handle incoming request untuk check user type
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        // log untuk debugging
        Log::info('CheckUserType Middleware', [
            'required_type' => $userType,
            'url' => $request->url(),
            'method' => $request->method(),
        ]);

        // cek apakah user sudah login
        if (!Auth::check()) {
            Log::warning('CheckUserType: User not authenticated');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        
        // validasi user object
        if (!$user || !isset($user->user_type)) {
            Log::error('CheckUserType: Invalid user object', [
                'user_id' => $user?->id ?? 'null',
            ]);
            
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Sesi tidak valid. Silakan login kembali');
        }

        Log::info('CheckUserType: User authenticated', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'required_type' => $userType,
        ]);

        // cek tipe user
        if ($user->user_type !== $userType) {
            Log::warning('CheckUserType: Type mismatch', [
                'user_id' => $user->id,
                'required' => $userType,
                'actual' => $user->user_type,
            ]);

            // redirect ke dashboard yang sesuai
            $redirectRoute = $this->getRedirectRoute($user->user_type);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Anda tidak memiliki akses ke halaman ini'
                ], 403);
            }

            return redirect()->route($redirectRoute)
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        Log::info('CheckUserType: Access granted');
        return $next($request);
    }

    /**
     * tentukan route redirect berdasarkan user type
     */
    private function getRedirectRoute(string $userType): string
    {
        return match($userType) {
            'student' => 'student.dashboard',
            'institution' => 'institution.dashboard',
            'admin' => 'admin.dashboard',
            default => 'home',
        };
    }
}