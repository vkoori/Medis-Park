<?php

namespace App\Providers;

use App\Utils\Response\Errors;
use App\Utils\Response\Success;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind('error_utils', Errors::class);
        $this->app->bind('success_utils', Success::class);
    }
}
