<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias middleware untuk digunakan di routes
        $middleware->alias([
            'dev.auth' => \App\Http\Middleware\CheckDevAuth::class,
        ]);
        
        // middleware global (jalan di semua request)
        // $middleware->append(\App\Http\Middleware\YourMiddleware::class);
        
        // middleware untuk web routes
        // $middleware->web(append: [
        //     \App\Http\Middleware\YourWebMiddleware::class,
        // ]);
        
        // middleware untuk api routes
        // $middleware->api(prepend: [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // custom exception handling
        // contoh:
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'Record not found.'
        //         ], 404);
        //     }
        // });
    })->create();