<?php

namespace App\Traits;

use Modules\User\Repositories\UserRepository;

trait ClassResolver
{
    protected function getUserRepository(): UserRepository
    {
        return app(UserRepository::class);
    }
}
