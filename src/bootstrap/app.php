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
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
         'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
          $exceptions->render(function (\App\Modules\Platform\Auth\Domain\Exceptions\OtpNotFoundException $e) {
        return response()->json([
            'ok' => false,
            'code' => 'AUTH_OTP_TOKEN_NOT_FOUND',
            'message' => $e->getMessage(),
            'errors' => (object) [],
        ], 422);
    });

    $exceptions->render(function (\App\Modules\Platform\Auth\Domain\Exceptions\OtpInvalidException $e) {
        return response()->json([
            'ok' => false,
            'code' => 'AUTH_OTP_INVALID',
            'message' => $e->getMessage(),
            'errors' => (object) [],
        ], 422);
    });

    $exceptions->render(function (\App\Modules\Platform\Auth\Domain\Exceptions\OtpExpiredException $e) {
        return response()->json([
            'ok' => false,
            'code' => 'AUTH_OTP_EXPIRED',
            'message' => $e->getMessage(),
            'errors' => (object) [],
        ], 422);
    });

    $exceptions->render(function (\App\Modules\Platform\Auth\Domain\Exceptions\OtpAlreadyUsedException $e) {
        return response()->json([
            'ok' => false,
            'code' => 'AUTH_OTP_ALREADY_USED',
            'message' => $e->getMessage(),
            'errors' => (object) [],
        ], 409);
    });
    })->create();
