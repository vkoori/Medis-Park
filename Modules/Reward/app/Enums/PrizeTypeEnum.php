<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum PrizeTypeEnum: string
{
    use EnumContract;

    case PRODUCT = 'product';
    case COIN = 'coin';
}
