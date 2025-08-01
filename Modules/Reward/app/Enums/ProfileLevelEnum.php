<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum ProfileLevelEnum: string
{
    use EnumContract;

    case LEVEL1 = 'level1';
    case LEVEL2 = 'level2';
    case LEVEL3 = 'level3';

    public function defaultReward(): int
    {
        return match ($this) {
            self::LEVEL1 => 10,
            self::LEVEL2 => 14,
            self::LEVEL3 => 20,
        };
    }
}
