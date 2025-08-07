<?php

namespace Modules\User\Enums;

use App\Utils\Enum\EnumContract;

enum OtpTypeEnum: string
{
    use EnumContract;

    case LOGIN_CUSTOMER = 'login_customer';
    case LOGIN_ADMIN = 'login_admin';

    public function expirationSeconds(): int
    {
        return match ($this) {
            self::LOGIN_CUSTOMER => 120,
            self::LOGIN_ADMIN => 60,
            default => 120,
        };
    }
}
