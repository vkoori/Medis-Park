<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum ProfileLevelEnum: string
{
    use EnumContract;

    case GOLD = 'gold';
    case SILVER = 'silver';
    case BRONZE = 'bronze';

    public function defaultReward(): int
    {
        return match ($this) {
            self::GOLD => 300,
            self::SILVER => 300,
            self::BRONZE => 0,
        };
    }
}
