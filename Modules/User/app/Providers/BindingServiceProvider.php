<?php

namespace Modules\User\Providers;

use Modules\User\Services\AuthService;
use Illuminate\Support\ServiceProvider;
use Modules\User\Services\UserInfoService;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\UserOtpRepository;
use Modules\User\Repositories\UserInfoRepository;

class BindingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(UserInfoRepository::class, UserInfoRepository::class);
        $this->app->singleton(UserOtpRepository::class, UserOtpRepository::class);
        $this->app->singleton(UserRepository::class, UserRepository::class);
        $this->app->singleton(UserInfoService::class, UserInfoService::class);
        $this->app->singleton(AuthService::class, AuthService::class);
    }
}
