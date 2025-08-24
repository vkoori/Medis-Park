<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Apis\V1\Admin\OrderController;

Route::get('/', [OrderController::class, 'index'])->name('index');
Route::patch('/{id}/used', [OrderController::class, 'used'])->name('used');
