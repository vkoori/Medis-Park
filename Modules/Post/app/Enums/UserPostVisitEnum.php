<?php

namespace Modules\Post\Enums;

use App\Utils\Enum\EnumContract;

enum UserPostVisitEnum: string
{
    use EnumContract;

    case NORMAL = 'normal';
    case ORDER = 'order';
}
