<?php

namespace Modules\Post\Exceptions;

use App\Exceptions\HttpException;

class PostVisitExceptions
{
    public static function notPostForVisit(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __(key: 'post::exceptions.not_post_for_visit')
        );
    }

    public static function alreadySeen(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __(key: 'post::exceptions.already_seen')
        );
    }

    public static function canNotUnlockPost(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __(key: 'post::postVisit.can_not_unlock_post', replace: [
                'time' => config(key: 'post.daily_reset_time'),
                'timezone' => config(key: 'post.daily_reset_timezone')
            ])
        );
    }
}
