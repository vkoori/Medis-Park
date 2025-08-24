<?php

use Illuminate\Support\Facades\Route;
use Modules\Coin\Http\Apis\V1\Customer\CoinController;

Route::get('balance', [CoinController::class, 'balance'])->name('balance');
Route::get('transactions', [CoinController::class, 'transactions'])->name('transactions');
