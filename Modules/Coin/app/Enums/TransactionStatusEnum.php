<?php

namespace Modules\Coin\Enums;

use App\Utils\Enum\EnumContract;

enum TransactionStatusEnum: string
{
    use EnumContract;

    case CONFIRMED = 'confirmed';
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
}
