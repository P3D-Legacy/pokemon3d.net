<?php

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web([
            \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\UpdateLastActiveAt::class,
            \Akaunting\Language\Middleware\SetLocale::class,
        ]);

        $middleware->throttleApi();
        $middleware->api([
            'api.json',
            'auth:sanctum',
            'permission:api',
        ]);

        $middleware->alias([
            'api.json' => \App\Http\Middleware\ApiJsonMiddleware::class,
            'gj.association' => \App\Http\Middleware\GameJoltAssociation::class,
            'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
            'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
