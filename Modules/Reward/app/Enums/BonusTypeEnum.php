<?php

namespace Modules\Reward\Enums;

use App\Utils\Enum\EnumContract;

enum BonusTypeEnum: string
{
    use EnumContract;

    case PROFILE = 'profile';
    case POST = 'post';
}
