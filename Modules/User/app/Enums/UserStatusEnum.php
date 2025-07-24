<?php

namespace Modules\User\Enums;

use App\Utils\Enum\EnumContract;

enum UserStatusEnum: string
{
    use EnumContract;

    case UNVERIFIED = 'unverified';
    case REGISTERING = 'registering';
    case REGISTERED = 'registered';
}
