<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // register middleware aliases
        $middleware->alias([
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            'student' => \App\Http\Middleware\EnsureStudentRole::class,
            'institution' => \App\Http\Middleware\EnsureInstitutionRole::class,
            'verified' => \App\Http\Middleware\EnsureVerified::class,
            'smooth.transition' => \App\Http\Middleware\SmoothPageTransition::class,
        ]);

        // global middleware yang diterapkan ke semua request
        $middleware->use([
            // \Illuminate\Http\Middleware\TrustProxies::class,
            // \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // middleware untuk web group
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // tambahkan smooth transition middleware untuk semua web routes
            \App\Http\Middleware\SmoothPageTransition::class,
        ]);

        // middleware untuk api group
        $middleware->api(prepend: [
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // redirect guests ke login page
        $middleware->redirectGuestsTo('/login');

        // redirect users ke dashboard setelah login
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            
            if (!$user) {
                return '/';
            }

            return match ($user->user_type) {
                'student' => '/student/dashboard',
                'institution' => '/institution/dashboard',
                'admin' => '/admin/dashboard',
                default => '/',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();