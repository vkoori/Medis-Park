<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Apis\Admin\V1\ProductController;

Route::post('/define', [ProductController::class, 'define'])->name('define');
