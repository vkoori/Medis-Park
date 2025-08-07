<?php

namespace Modules\User\Exceptions;

use App\Exceptions\HttpException;
use App\Exceptions\HttpExceptionCodes;

class AuthExceptions
{
    public static function expiredOtp(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('user::exceptions.expired_otp'),
            code: HttpExceptionCodes::EXPIRED_OTP_CODE
        );
    }

    public static function invalidOtp(): HttpException
    {
        return new HttpException(
            statusCode: 400,
            messageBag: __('user::exceptions.invalid_otp'),
            code: HttpExceptionCodes::INVALID_OTP_CODE
        );
    }

    public static function forbiddenForNonAdmin(): HttpException
    {
        return new HttpException(
            statusCode: 403,
            messageBag: __('user::exceptions.forbidden_for_non_admin')
        );
    }
}
