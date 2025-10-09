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
        // trust proxies untuk Railway/Cloudflare
        $middleware->trustProxies(at: '*');
        
        // register middleware aliases
        $middleware->alias([
            'check.user.type' => \App\Http\Middleware\CheckUserType::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'ensure.student' => \App\Http\Middleware\EnsureStudentRole::class,
            'ensure.institution' => \App\Http\Middleware\EnsureInstitutionRole::class,
            'ensure.verified' => \App\Http\Middleware\EnsureVerified::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();