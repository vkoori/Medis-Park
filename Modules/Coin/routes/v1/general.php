<?php

use Illuminate\Support\Facades\Route;
use Modules\Coin\Http\Apis\V1\CoinController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('coins', CoinController::class)->names('coin');
});
