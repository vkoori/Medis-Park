<?php

namespace Modules\Coin\Enums;

use App\Utils\Enum\EnumContract;

enum TransactionReferenceTypeEnum: string
{
    use EnumContract;

    case UNLOCKED_REWARD = 'unlocked_reward';
    case ORDER = 'order';
    case CRM = 'crm';
}
