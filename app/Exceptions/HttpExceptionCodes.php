<?php

namespace App\Exceptions;

/**
 * Exception codes used across the HttpException class
 *
 * 0 for unhandled error code.
 * 1-999 for general application errors.
 * 1000-1999 for validation & input errors.
 * 2000-2999 for authentication & security errors.
 * 3000-3999 for infrastructure-related errors.
 * 4000-4999 for external service errors.
 * 5000+ for any other business logic errors.
 *
 * @see HttpException
 */
readonly class HttpExceptionCodes
{
    # General exceptions (1-999)
    public const UNHANDLED_ERROR_CODE = 0;
    public const THROTTLE_REQUESTS = 1;

    # Validation exceptions (1000-1999)

    # Authentication exceptions (2000-2999)
    public const EXPIRED_OTP_CODE = 2001;
    public const INVALID_OTP_CODE = 2002;

    # Infrastructure exceptions (3000-3999)

    # ThirdParty exceptions (4000-4999)

    # BusinessLogic exceptions (5000+)
}
