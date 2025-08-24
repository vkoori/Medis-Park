<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum PrizeUnlockTypeEnum: string
{
    use EnumContract;

    case NORMAL = 'normal';
    case ORDER = 'order';
}
