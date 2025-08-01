<?php

namespace Modules\User\Services;

use App\Exceptions\NotImplementedException;
use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Octane\Facades\Octane;
use Modules\Notification\Notifications\OtpNotification;
use Modules\User\Enums\OtpTypeEnum;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Exceptions\AuthExceptions;
use Modules\User\Models\User;
use Modules\User\Models\UserOtp;
use Modules\User\Support\FormattedPhoneNumber;

class AuthService
{
    use ClassResolver;

    public function findOrCreateUnverifiedUser(FormattedPhoneNumber $mobile): User
    {
        $user = $this->getUserRepository()->firstOrCreate(
            attributes: [
                'mobile' => $mobile
            ],
            values: [
                'status' => UserStatusEnum::UNVERIFIED
            ]
        );

        $userInfo = $this->getUserInfoRepository()->first(
            conditions: ['user_id' => $user->id]
        );
        if (!$userInfo) {
            $crmUserInfo = $this->getCrmService()->getUserInfo(
                mobile: $mobile->formatNational()
            );

            if ($crmUserInfo) {
                $this->getUserInfoRepository()->insertOrIgnore(values: [
                    'user_id' => $user->id,
                    'national_code' => $crmUserInfo->getNationalCode(),
                    'first_name' => $crmUserInfo->getFirstName(),
                    'last_name' => $crmUserInfo->getLastName(),
                ]);
            }
        }

        $this->sendOtp(user: $user, type: OtpTypeEnum::LOGIN);

        return $user;
    }

    public function loginCustomerByOtp(int $userId, string $otp, string $issuer): array
    {
        [$activeOtp, $user] = Octane::concurrently(tasks: [
            fn(): ?UserOtp => $this->getUserOtpRepository()->findActiveOtp(
                userId: $userId,
                type: OtpTypeEnum::LOGIN,
            ),
            fn(): User => $this->getUserRepository()->findByIdOrFail(
                modelId: $userId
            ),
        ]);

        if (!$activeOtp) {
            throw AuthExceptions::expiredOtp();
        }
        if (!Hash::check(value: $otp, hashedValue: $activeOtp->otp_hash)) {
            throw AuthExceptions::invalidOtp();
        }

        DB::transaction(callback: function () use ($activeOtp, $user) {
            $this->getUserOtpRepository()->batchUpdate(
                conditions: ['id' => $activeOtp->id],
                values: ['used' => true]
            );

            if ($user->status == UserStatusEnum::UNVERIFIED) {
                $this->getUserRepository()->batchUpdate(
                    conditions: ['id' => $user->id],
                    values: ['status' => UserStatusEnum::REGISTERING]
                );
            }
        });

        $scope = match ($user->status) {
            UserStatusEnum::UNVERIFIED, UserStatusEnum::REGISTERING => 'lead',
            UserStatusEnum::REGISTERED => 'customer',
            default => throw new NotImplementedException()
        };
        $jwt = $user->accessToken(issuer: $issuer, scopes: [$scope]);

        return ['scope' => $scope] + $jwt;
    }

    public function refresh(string $refreshToken): array
    {
        return (new User())->refreshToken(refreshToken: $refreshToken);
    }

    public function regenerateCustomerToken(string $currentToken, string $issuer, User $user)
    {
        $user->revokeToken(token: $currentToken);
        $jwt = $user->accessToken(issuer: $issuer, scopes: ['customer']);

        return ['scope' => 'customer'] + $jwt;
    }

    protected function sendOtp(User $user, OtpTypeEnum $type): void
    {
        $activeOtp = $this->getUserOtpRepository()->findActiveOtp(
            userId: $user->id,
            type: $type
        );

        if (!$activeOtp) {
            $otp = rand(min: 100000, max: 999999);
            $this->getUserOtpRepository()->create(attributes: [
                'user_id' => $user->id,
                'otp_hash' => $otp,
                'type' => $type,
                'used' => false,
                'expires_at' => now()->addSeconds(value: $type->expirationSeconds()),
            ]);

            $user->notify(instance: new OtpNotification(code: $otp));
        }
    }
}
