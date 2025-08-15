<?php

namespace Modules\User\Database\Seeders;

use App\Traits\ClassResolver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use libphonenumber\PhoneNumberUtil;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Support\FormattedPhoneNumber;

class SuperAdminSeeder extends Seeder
{
    use ClassResolver;

    public function run(): void
    {
        $superAdmins = config('user.admin.super_admins');
        $superAdmins = array_map(
            callback: fn($superAdmin): FormattedPhoneNumber => new FormattedPhoneNumber(
                phoneNumber: PhoneNumberUtil::getInstance()->parse(
                    numberToParse: trim($superAdmin),
                    defaultRegion: 'IR'
                )
            ),
            array: $superAdmins
        );

        foreach ($superAdmins as $mobile) {
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
