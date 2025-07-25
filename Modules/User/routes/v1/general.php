<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Apis\V1\AuthController;

Route::post('get-access', [AuthController::class, 'getAccess'])->name('getAccess')->middleware('throttle:6,1');
