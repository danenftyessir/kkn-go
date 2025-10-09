<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EnsureInstitutionRole
{
    /**
     * handle request untuk memastikan user adalah instansi
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('EnsureInstitutionRole Middleware', [
            'url' => $request->url(),
        ]);

        if (!Auth::check()) {
            Log::warning('EnsureInstitutionRole: User not authenticated');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated'
                ], 401);
            }
            
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isInstitution()) {
            Log::warning('EnsureInstitutionRole: Access denied', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Halaman ini hanya untuk instansi'
                ], 403);
            }
            
            abort(403, 'Unauthorized. Halaman ini hanya untuk instansi.');
        }

        Log::info('EnsureInstitutionRole: Access granted', [
            'user_id' => $user->id,
        ]);

        return $next($request);
    }
}