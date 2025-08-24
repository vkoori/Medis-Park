<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Apis\V1\Customer\OrderController;

Route::post('/', [OrderController::class, 'store'])->name('store');
