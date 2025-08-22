<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\Admin\V1\ComponentController;
use Modules\Product\Http\Apis\Admin\V1\PostPriceController;
use Modules\Product\Http\Apis\Admin\V1\MonthlyItemController;

Route::prefix('component')->name('component.')->group(function () {
    Route::post('/', [ComponentController::class, 'store'])->name('store');
    Route::get('/', [ComponentController::class, 'index'])->name('index');
    Route::get('/{id}', [ComponentController::class, 'show'])->name('show');
    Route::post('/{id}/price', [ComponentController::class, 'newPrice'])->name('new-price');
});
Route::prefix('post-price')->name('post-price.')->group(function () {
    Route::get('/', [PostPriceController::class, 'index'])->name('index');
    Route::post('/', [PostPriceController::class, 'newPrice'])->name('newPrice');
});
Route::prefix('monthly-items')->name(value: 'monthly-items.')->group(function () {
    Route::post('/component', [MonthlyItemController::class, 'addComponent'])->name('add-component');
    Route::post('/coin', [MonthlyItemController::class, 'addCoin'])->name('add-coin');
    Route::get('/{jYear}-{jMonth}', [MonthlyItemController::class, 'index'])->name('index');
    Route::patch('/ordering/increase', [MonthlyItemController::class, 'increase'])->name('increase');
    Route::patch('/ordering/decrease', [MonthlyItemController::class, 'decrease'])->name('decrease');
});
