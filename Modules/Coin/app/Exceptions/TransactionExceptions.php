<?php

namespace Modules\Coin\Exceptions;

use App\Exceptions\HttpException;

class TransactionExceptions
{
    public static function dontHaveEnoughCoins()
    {
        return new HttpException(
            statusCode: 402,
            messageBag: __('coin::exceptions.dont_have_enough_coins')
        );
    }
}
