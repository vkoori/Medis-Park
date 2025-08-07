<?php

namespace Modules\User\Database\Seeders;

use App\Traits\ClassResolver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Enums\UserStatusEnum;

class SuperAdminSeeder extends Seeder
{
    use ClassResolver;

    public function run(): void
    {
        foreach (config('user.admin.super_admins') as $mobile) {
            DB::transaction(function () use ($mobile) {
                $user = $this->getUserRepository()->firstOrCreate(
                    attributes: [
                        'mobile' => $mobile
                    ],
                    values: [
                        'status' => UserStatusEnum::UNVERIFIED
                    ]
                );

                if (!$user->hasRole('super_admin')) {
                    $user->assignRole('super_admin');
                }
            });
        }
    }
}
