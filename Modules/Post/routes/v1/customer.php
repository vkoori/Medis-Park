<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Apis\Customer\V1\PostController;
use Modules\Post\Http\Apis\Customer\V1\PostVisitController;

Route::get('/{jYear}-{jMonth}', [PostController::class, 'getMonthly'])->name('monthly-posts');
Route::get('/seen', [PostVisitController::class, 'unlockPostNormally'])->name('unlock-post-normally');
