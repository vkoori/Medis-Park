<?php

namespace App\Traits;

use Modules\Crm\Services\CrmService;
use Modules\User\Repositories\UserInfoRepository;
use Modules\User\Repositories\UserOtpRepository;
use Modules\User\Repositories\UserRepository;

trait ClassResolver
{
    // Repositories
    protected function getUserRepository(): UserRepository
    {
        return app(UserRepository::class);
    }
    protected function getUserOtpRepository(): UserOtpRepository
    {
        return app(UserOtpRepository::class);
    }
    protected function getUserInfoRepository(): UserInfoRepository
    {
        return app(UserInfoRepository::class);
    }
    // Services
    protected function getCrmService(): CrmService
    {
        return app(CrmService::class);
    }
}
