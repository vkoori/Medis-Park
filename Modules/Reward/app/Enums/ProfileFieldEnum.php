<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum ProfileFieldEnum: string
{
    use EnumContract;

    case FIRST_NAME = 'first_name';
    case LAST_NAME = 'last_name';
    case NATIONAL_CODE = 'national_code';
    case EMAIL = 'email';
    case ADDRESS = 'address';

    public function defaultLevel(): ProfileLevelEnum
    {
        return match ($this) {
            self::FIRST_NAME => ProfileLevelEnum::BRONZE,
            self::LAST_NAME => ProfileLevelEnum::BRONZE,
            self::NATIONAL_CODE => ProfileLevelEnum::SILVER,
            self::EMAIL => ProfileLevelEnum::SILVER,
            self::ADDRESS => ProfileLevelEnum::GOLD,
        };
    }
}
