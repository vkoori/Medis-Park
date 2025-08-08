<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Apis\Admin\V1\PostController;

Route::post('/', [PostController::class, 'store'])->name('store');
Route::get('/', [PostController::class, 'index'])->name('index');
Route::get('/{id}', [PostController::class, 'show'])->name('show');
Route::patch('/{id}', [PostController::class, 'update'])->name('update');
