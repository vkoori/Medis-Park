<?php

use Illuminate\Support\Facades\Route;
use Modules\Reward\Http\Apis\V1\Customer\PrizeController;

Route::prefix('prize')->name('prize')->group(function () {
    Route::get('/{jYear}-{jMonth}', [PrizeController::class, 'index'])->name('index');
    Route::post('/{jYear}-{jMonth}/unlock', [PrizeController::class, 'unlock'])->name('unlock');
});
