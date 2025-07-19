<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Apis\V1\AuthController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', AuthController::class)->names('user');
});
