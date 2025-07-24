<?php

namespace Modules\User\Enums;

use App\Utils\Enum\EnumContract;

enum OtpTypeEnum: string
{
    use EnumContract;

    case LOGIN = 'login';
    case PASSWORD_RESET = 'password_reset';
}
