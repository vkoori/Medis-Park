<?php

namespace Modules\Order\Exceptions;

use App\Exceptions\HttpException;

class OrderExceptions
{
    public static function alreadyBuy(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('order::exceptions.already_buy')
        );
    }

    public static function notFound(): HttpException
    {
        return new HttpException(
            statusCode: 404,
            messageBag:  __('order::exceptions.not_found')
        );
    }

    public static function alreadyUsed(): HttpException
    {
        return new HttpException(
            statusCode: 404,
            messageBag:  __('order::exceptions.already_used')
        );
    }
}
