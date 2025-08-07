<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Apis\Member\V1\AuthController;

Route::get('logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('throttle:1,1');
