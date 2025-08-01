<?php

use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Apis\V1\CrmController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('crms', CrmController::class)->names('crm');
});
