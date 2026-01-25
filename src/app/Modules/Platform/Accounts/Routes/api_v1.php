<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Modules\Platform\Accounts\Http\Controllers\Api\V1\AccountController;
use App\Modules\Platform\Accounts\Http\Controllers\Api\V1\TeamController;

Route::prefix('api/v1')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts/switch', [AccountController::class, 'switch']);

    Route::middleware(['active.account'])->group(function () {
        Route::prefix('/account')->group(function () {
            Route::middleware(['account.role:owner'])->group(function () {
                Route::get('/team', [TeamController::class, 'index']);
                Route::post('/team/invite', [TeamController::class, 'invite']);
                Route::patch('/team/{accountUserId}', [TeamController::class, 'update']);
                Route::delete('/team/{accountUserId}', [TeamController::class, 'destroy']);
            });
        });
    });
});
