<?php

declare(strict_types=1);


use App\Modules\Platform\Auth\Http\Controllers\Api\V1\Me\MeController;
use Illuminate\Support\Facades\Route;
use App\Modules\Platform\Auth\Http\Controllers\Api\V1\Otp\RequestOtpController;
use App\Modules\Platform\Auth\Http\Controllers\Api\V1\Otp\VerifyOtpController;


Route::prefix('api/v1')
    ->middleware(['api'])
    ->group(static function (): void {
        Route::post('/auth/otp/request', RequestOtpController::class);
        Route::post('/auth/otp/verify', VerifyOtpController::class);

    

        Route::middleware('auth:sanctum')->get('/me', MeController::class);


      Route::middleware(['auth:sanctum', 'role:platform-admin'])
    ->prefix('admin')
    ->group(static function (): void {
        Route::get('/ping', static fn () => response()->json(['ok' => true, 'scope' => 'admin']));
        Route::get('/me', static fn (\Illuminate\Http\Request $request) => response()->json([
            'ok' => true,
            'user' => [
                'id' => $request->user()?->id,
                'roles' => $request->user()?->getRoleNames(),
                'permissions' => $request->user()?->getAllPermissions()->pluck('name')->values()->all(),
            ],
        ]));
    });



    });
