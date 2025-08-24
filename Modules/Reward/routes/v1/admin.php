<?php

use Illuminate\Support\Facades\Route;
use Modules\Reward\Http\Apis\V1\Admin\PrizeController;

Route::prefix('prize')->name('prize')->group(function() {
    Route::post('/{jYear}-{jMonth}/product/{productId}', [PrizeController::class, 'addProduct'])->name('add-product');
    Route::post('/{jYear}-{jMonth}/coin', [PrizeController::class, 'addCoin'])->name('add-coin');
    Route::get('/{jYear}-{jMonth}', [PrizeController::class, 'index'])->name('index');
});
