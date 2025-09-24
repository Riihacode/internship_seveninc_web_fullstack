<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware): void {
//         //
//     })
//     ->withExceptions(function (Exceptions $exceptions): void {
//         //
//     })->create();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // 'auth'      => \App\Http\Middleware\Authenticate::class,
            // 'verified'  => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'auth'      => \Illuminate\Auth\Middleware\Authenticate::class,
            'verified'  => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // Custom kita
            'role'      => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();