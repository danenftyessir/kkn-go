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
        // register middleware aliases untuk Laravel 11
        $middleware->alias([
            // alias untuk check user type (digunakan di web routes)
            'check.user.type' => \App\Http\Middleware\CheckUserType::class,
            
            // alias untuk verified email
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            
            // alias untuk ensure student role
            'ensure.student' => \App\Http\Middleware\EnsureStudentRole::class,
            
            // alias untuk ensure institution role
            'ensure.institution' => \App\Http\Middleware\EnsureInstitutionRole::class,
        ]);
        
        // register middleware global (opsional)
        // $middleware->append(\App\Http\Middleware\SmoothPageTransition::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();