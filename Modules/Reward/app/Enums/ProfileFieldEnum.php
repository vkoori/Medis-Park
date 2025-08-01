<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum ProfileFieldEnum: string
{
    use EnumContract;

    case FIRST_NAME = 'first_name';
    case LAST_NAME = 'last_name';
    case NATIONAL_CODE = 'national_code';

    public function defaultLevel(): ProfileLevelEnum
    {
        return match ($this) {
            self::FIRST_NAME => ProfileLevelEnum::LEVEL3,
            self::LAST_NAME => ProfileLevelEnum::LEVEL2,
            self::NATIONAL_CODE => ProfileLevelEnum::LEVEL1,
        };
    }
}
