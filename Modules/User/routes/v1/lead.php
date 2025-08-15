<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Apis\Lead\V1\UserInfoController;

Route::get('user-info', [UserInfoController::class, 'show'])
    ->name('show');
Route::post('user-info', [UserInfoController::class, 'storeOrUpdate'])
    ->name('storeOrUpdate');
