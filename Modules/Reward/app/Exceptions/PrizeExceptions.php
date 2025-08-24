<?php

namespace Modules\Reward\Exceptions;

use App\Exceptions\HttpException;

class PrizeExceptions
{
    public static function alreadyConverted()
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('reward::exceptions.already_prize')
        );
    }
}
