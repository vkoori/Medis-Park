<?php

namespace Modules\Order\Enums;

use App\Utils\Enum\EnumContract;

enum OrderStatusEnum: string
{
    use EnumContract;

    case REWARDED = 'rewarded';
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';

    public static function completeStatuses()
    {
        return [
            self::REWARDED,
            self::PAID
        ];
    }
}
