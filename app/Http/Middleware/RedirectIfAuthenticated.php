<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * handle request untuk redirect user yang sudah login
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                Log::info('RedirectIfAuthenticated: User already logged in', [
                    'user_id' => $user->id,
                    'user_type' => $user->user_type,
                ]);
                
                // redirect berdasarkan user type
                $redirectPath = match ($user->user_type) {
                    'student' => route('student.dashboard'),
                    'institution' => route('institution.dashboard'),
                    'admin' => route('admin.dashboard'),
                    default => route('home'),
                };

                return redirect($redirectPath);
            }
        }

        return $next($request);
    }
}