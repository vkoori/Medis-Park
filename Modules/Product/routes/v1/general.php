<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\V1\ProductController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class)->names('product');
});
