<?php

namespace Modules\Product\Exceptions;

use App\Exceptions\HttpException;

class MonthlyItemExceptions
{
    public static function canNotIncreaseHighestPriority(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('product::exceptions.can_not_increase_highest_priority')
        );
    }

    public static function canNotDecreaseLowestPriority(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('product::exceptions.can_not_decrease_lowest_priority')
        );
    }
}
