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

    public static function canNotUnlockPrize(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __(key: 'reward::exceptions.can_not_unlock_post', replace: [
                'time' => config(key: 'reward.daily_reset_time'),
                'timezone' => config(key: 'reward.daily_reset_timezone')
            ])
        );
    }

    public static function allPrizedOpen()
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('reward::exceptions.all_prized_open')
        );
    }
}
