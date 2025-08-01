<?php

namespace Modules\User\Dto;

use App\Utils\Dto\BaseDto;

class UserInfoDto extends BaseDto
{
    public readonly ?string $nationalCode;
    public readonly ?string $firstName;
    public readonly ?string $lastName;
}
