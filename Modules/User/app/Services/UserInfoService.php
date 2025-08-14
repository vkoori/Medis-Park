<?php

namespace Modules\User\Services;

use App\Traits\ClassResolver;
use Modules\User\Models\User;
use App\Dto\UserInfoUpdatedEvent;
use Modules\User\Dto\UserInfoDto;
use Modules\User\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Exceptions\UserExceptions;

class UserInfoService
{
    use ClassResolver;

    public function getUser(int $userId, bool $findOrFail = true): ?User
    {
        $user = $this->getUserRepository()->findByIdOrFail(modelId: $userId);
        if ($findOrFail && !$user) {
            throw UserExceptions::userNotFound();
        }

        return $user;
    }

    public function getUserInfo(int $userId): ?UserInfo
    {
        return $this->getUserInfoRepository()->first(
            conditions: ['user_id' => $userId]
        );
    }

    public function updateOrCreate(int $userId, UserInfoDto $dto): UserInfo
    {
        $userInfo = DB::transaction(callback: function () use ($userId, $dto): UserInfo {
            $userInfo = $this->getUserInfoRepository()->updateOrCreate(
                attributes: ['user_id' => $userId],
                values: [
                    "national_code" => $dto->nationalCode,
                    "first_name" => $dto->firstName,
                    "last_name" => $dto->lastName,
                ]
            )->loadMissing(['user']);

            if ($userInfo->user->status == UserStatusEnum::REGISTERING) {
                $this->getUserRepository()->batchUpdate(
                    conditions: ['id' => $userId],
                    values: ['status' => UserStatusEnum::REGISTERED]
                );
            }

            return $userInfo;
        });

        event(new UserInfoUpdatedEvent(
            userId: $userId,
            mobile: $userInfo->user->mobile->formatNational(),
            nationalCode: $userInfo->national_code,
            firstName: $userInfo->first_name,
            lastName: $userInfo->last_name,
        ));

        return $userInfo;
    }
}
