<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EnsureStudentRole
{
    /**
     * handle request untuk memastikan user adalah mahasiswa
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('EnsureStudentRole Middleware', [
            'url' => $request->url(),
        ]);

        if (!Auth::check()) {
            Log::warning('EnsureStudentRole: User not authenticated');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated'
                ], 401);
            }
            
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isStudent()) {
            Log::warning('EnsureStudentRole: Access denied', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Halaman ini hanya untuk mahasiswa'
                ], 403);
            }
            
            abort(403, 'Unauthorized. Halaman ini hanya untuk mahasiswa.');
        }

        Log::info('EnsureStudentRole: Access granted', [
            'user_id' => $user->id,
        ]);

        return $next($request);
    }
}