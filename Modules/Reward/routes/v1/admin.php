<?php

use Illuminate\Support\Facades\Route;
use Modules\Reward\Http\Apis\V1\Admin\PrizeController;

Route::prefix('prize')->name('prize')->group(function() {
    Route::post('/{jYear}-{jMonth}', [PrizeController::class, 'store'])->name('store');
    Route::get('/{jYear}-{jMonth}', [PrizeController::class, 'index'])->name('index');
});
