<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Apis\General\V1\AuthController;

Route::post('get-access', [AuthController::class, 'getAccess'])
    ->name('getAccess')
    ->middleware('throttle:6,1');
Route::post('check-otp', [AuthController::class, 'checkOtp'])
    ->name('checkOtp')
    ->middleware('throttle:10,1');
Route::post('refresh', [AuthController::class, 'refresh'])
    ->name('refresh')
    ->middleware('throttle:3,1');
