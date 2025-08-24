<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\Customer\V1\ProductController;

Route::prefix('item')->name('item.')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
});
