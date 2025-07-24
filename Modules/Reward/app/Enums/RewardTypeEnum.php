<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum RewardTypeEnum: string
{
    use EnumContract;

    case MONTHLY_COIN = 'monthly_coin';
    case MONTHLY_PRODUCT = 'monthly_product';
    case PROFILE = 'profile';
}
