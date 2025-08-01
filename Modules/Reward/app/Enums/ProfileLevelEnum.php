<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum ProfileLevelEnum: string
{
    use EnumContract;

    case LEVEL1 = 'level1'; // Gold
    case LEVEL2 = 'level2'; // Silver
    case LEVEL3 = 'level3'; // Bronze

    public function defaultReward(): int
    {
        return match ($this) {
            self::LEVEL1 => 20,
            self::LEVEL2 => 14,
            self::LEVEL3 => 10,
        };
    }
}
