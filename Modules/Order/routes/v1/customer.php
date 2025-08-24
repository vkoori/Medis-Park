<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Apis\V1\Customer\OrderController;

Route::post('/product/{productId}', [OrderController::class, 'productOrder'])->name('product-order');
