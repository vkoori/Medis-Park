<?php

use Illuminate\Support\Facades\Route;
use Modules\Reward\Http\Apis\V1\RewardController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('rewards', RewardController::class)->names('reward');
});
