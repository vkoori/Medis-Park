<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

Route::get('/sms', function () {
    if (!app()->isLocal()) {
        throw new RouteNotFoundException();
    }

    $path = storage_path('logs/sms.log');
    if (!file_exists($path)) {
        abort(404, 'Log file not found.');
    }

    return response()->file($path, [
        'Content-Type' => 'text/plain'
    ]);
})->name('sms');
