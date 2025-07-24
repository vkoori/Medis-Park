<?php

namespace Modules\Coin\Enums;

use App\Utils\Enum\EnumContract;

enum TransactionReferenceTypeEnum: string
{
    use EnumContract;

    case REWARD_UNLOCKED = 'reward_unlocked';
    case ORDER = 'order';
    case CRM = 'crm';
}
