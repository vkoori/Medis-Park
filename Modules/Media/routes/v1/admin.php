<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Apis\Admin\V1\MediaController;

Route::get('/disks', [MediaController::class, 'disks'])->name('disks');
Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
