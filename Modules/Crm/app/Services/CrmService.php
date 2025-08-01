<?php

namespace Modules\Crm\Services;

use Modules\Crm\Dto\UserInfoDto;

/**
 * @todo this class must be update
 */
class CrmService
{
    private readonly string $baseUrl;
    private readonly string $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://example.com';
        $this->apiKey = '123456';
    }

    public function getUserInfo(string $mobile, bool $throwException = false): ?UserInfoDto
    {
        return new UserInfoDto(
            mobile: $mobile,
            nationalCode: rand(1000000000, 9999999999),
            firstName: fake()->firstName(),
            lastName: fake()->lastName()
        );
    }

    public function updateUserInfo(UserInfoDto $userInfoDto): bool
    {
        return true;
    }

    public function insertUserInfo(UserInfoDto $userInfoDto): bool
    {
        return true;
    }
}
