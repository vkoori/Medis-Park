<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Apis\Admin\V1\MediaController;

Route::get('/disks/all', [MediaController::class, 'allDisks'])->name('allDisks');
Route::get('/disks/private', [MediaController::class, 'privateDisks'])->name('privateDisks');
Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
