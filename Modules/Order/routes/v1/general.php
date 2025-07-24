<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Apis\V1\OrderController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('order');
});
