<?php

namespace Modules\User\Exceptions;

use App\Exceptions\HttpException;

class UserExceptions
{
    public static function userNotFound(): HttpException
    {
        return new HttpException(
            statusCode: 404,
            messageBag: __('user::exceptions.user_not_found')
        );
    }
}
