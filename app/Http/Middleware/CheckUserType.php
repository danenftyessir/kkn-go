<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * handle request untuk mengecek user type
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // cek apakah user type sesuai
        if ($user->user_type !== $userType) {
            // redirect ke dashboard yang sesuai
            return match ($user->user_type) {
                'student' => redirect()->route('student.dashboard'),
                'institution' => redirect()->route('institution.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return $next($request);
    }
}