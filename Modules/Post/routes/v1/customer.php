<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Apis\Customer\V1\PostController;
use Modules\Post\Http\Apis\Customer\V1\PostVisitController;

Route::get('/{jYear}-{jMonth}', [PostController::class, 'getMonthly'])->name('monthly-posts');
Route::post('/seen', [PostVisitController::class, 'unlockPostNormally'])->name('unlock-post-normally');
Route::post('/{postId}/buy', [PostVisitController::class, 'unlockPostByCoin'])->name('unlock-post-coin');
