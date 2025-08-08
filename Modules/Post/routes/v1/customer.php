<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Apis\Customer\V1\PostVisitController;

Route::get('/seen', [PostVisitController::class, 'unlockPostNormally'])->name('unlock-post-normally');
Route::get('/{jYear}-{jMonth}', [PostVisitController::class, 'unlockedPosts'])->name('unlocked-posts');
