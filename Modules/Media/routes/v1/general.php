<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Apis\V1\MediaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('media', MediaController::class)->names('media');
});
