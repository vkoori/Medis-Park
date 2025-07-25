<?php

namespace Modules\User\Services;

use App\Traits\ClassResolver;
use Modules\Notification\Notifications\OtpNotification;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Support\FormattedPhoneNumber;

class AuthService
{
    use ClassResolver;

    public function findOrCreateUnverifiedUser(FormattedPhoneNumber $mobile)
    {
        $user = $this->getUserRepository()->firstOrCreate(
            attributes: [
                'mobile' => $mobile
            ],
            values: [
                'status' => UserStatusEnum::UNVERIFIED
            ]
        );

        $user->notify(new OtpNotification(13456));

        dd($user->status == UserStatusEnum::UNVERIFIED);
    }
}
