<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\Customer\V1\MonthlyItemController;

Route::prefix('monthly-items')->name(value: 'monthly-items.')->group(function () {
    Route::get('/{jYear}-{jMonth}', [MonthlyItemController::class, 'index'])->name('index');
});
