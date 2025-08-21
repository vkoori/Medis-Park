<?php

namespace Modules\User\Dto;

use App\Utils\Dto\BaseDto;

class UserInfoDto extends BaseDto
{
    public readonly ?string $nationalCode;
    public readonly ?string $email;
    public readonly ?string $firstName;
    public readonly ?string $lastName;
    public readonly ?string $address;
    // public readonly ?string $addressLabel;
    // public readonly ?string $addressPostalCode;
    // public readonly ?string $addressLat;
    // public readonly ?string $addressLong;
}
