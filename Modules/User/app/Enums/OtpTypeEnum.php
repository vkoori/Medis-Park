<?php

namespace Modules\User\Enums;

use App\Utils\Enum\EnumContract;

enum OtpTypeEnum: string
{
    use EnumContract;

    case LOGIN = 'login';
    case PASSWORD_RESET = 'password_reset';

    public function expirationSeconds(): int
    {
        return match ($this) {
            self::LOGIN => 120,
            self::PASSWORD_RESET => 180,
            default => 120,
        };
    }
}
