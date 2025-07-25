<?php

namespace Modules\Notification\Exceptions;

use Exception;

abstract class NotificationException extends Exception
{
    const SMS_EXCEPTION_CODE = 1;
    const NOT_AVAILABLE_PROVIDER = 2;
}
