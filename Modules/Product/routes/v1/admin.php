<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\Admin\V1\ProductController;
use Modules\Product\Http\Apis\Admin\V1\PostPriceController;

Route::prefix('item')->name('item.')->group(function () {
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::post('/{id}/price', [ProductController::class, 'newPrice'])->name('new-price');
});
Route::prefix('post-price')->name('post-price.')->group(function () {
    Route::get('/', [PostPriceController::class, 'index'])->name('index');
    Route::post('/', [PostPriceController::class, 'newPrice'])->name('newPrice');
});
