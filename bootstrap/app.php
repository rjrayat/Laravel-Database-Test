<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // JWT Middleware alias
        $middleware->alias([
            'jwt' => \App\Http\Middleware\JWTMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        // Inertia Middleware
        $middleware->web(
            append:[
                HandleInertiaRequests::class
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


