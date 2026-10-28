<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\OwnerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [\App\Http\Middleware\EnsureAccountAvailable::class]);
        $middleware->web(prepend: [\App\Http\Middleware\UnblockExpiredAccounts::class, \App\Http\Middleware\ArchiveExpiredPosts::class]);

        $middleware->validateCsrfTokens(except: ['payments/stripe/webhook']);

        $middleware->alias([

            // Existing Role Middleware
            'role' => CheckRole::class,

            // Owner Super Admin Middleware
            'owner' => OwnerMiddleware::class,

        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
