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
            self::GOLD => 20,
            self::SILVER => 14,
            self::BRONZE => 10,
        };
    }
}
