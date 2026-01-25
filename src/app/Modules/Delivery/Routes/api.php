<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Modules\Delivery\Http\Controllers\AccountDeliveriesController;
use App\Modules\Delivery\Http\Controllers\MyDeliveriesController;

Route::prefix('api')
    ->middleware(['auth:sanctum', 'active.account', 'subscribed:delivery'])
    ->group(function () {

        // Owner/Admin (account-wide)
        Route::prefix('delivery')
            ->middleware(['account.perm:delivery.manage'])
            ->group(function () {
                Route::get('/orders', [AccountDeliveriesController::class, 'index']);
                Route::post('/orders', [AccountDeliveriesController::class, 'store']);
                Route::post('/orders/{deliveryId}/assign', [AccountDeliveriesController::class, 'assign']);
                Route::post('/orders/{deliveryId}/reassign', [AccountDeliveriesController::class, 'reassign']);
                Route::post('/orders/{deliveryId}/collection', [AccountDeliveriesController::class, 'recordCollection']);
            });

        // Delivery staff (my assignments)
        Route::prefix('my/delivery')
            ->middleware(['account.perm:delivery.self'])
            ->group(function () {
                Route::get('/orders', [MyDeliveriesController::class, 'index']);
                Route::post('/orders/{deliveryId}/status', [MyDeliveriesController::class, 'updateStatus']);
            });
    });
