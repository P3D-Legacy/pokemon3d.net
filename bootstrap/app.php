<?php

use Akaunting\Language\Middleware\SetLocale;
use App\Http\Middleware\ApiJsonMiddleware;
use App\Http\Middleware\GameJoltAssociation;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\UpdateLastActiveAt;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Jetstream\Http\Middleware\AuthenticateSession;
use Sentry\Laravel\Integration;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () { // Include custom route files
            require __DIR__.'/../routes/fortify.php';
            require __DIR__.'/../routes/jetstream.php';
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->web([
            AuthenticateSession::class,
            UpdateLastActiveAt::class,
            SetLocale::class,
            HandleInertiaRequests::class,
        ]);

        $middleware->throttleApi();
        $middleware->api([
            'api.json',
            'auth:sanctum',
            'permission:api',
        ]);

        $middleware->alias([
            'api.json' => ApiJsonMiddleware::class,
            'gj.association' => GameJoltAssociation::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'No records found.',
                ], 404);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        });

        $exceptions->render(function (UnauthorizedException $exception, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        });
    })->create();
