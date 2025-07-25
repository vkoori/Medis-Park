<?php

namespace Modules\Notification\Exceptions;

use Throwable;

class SmsNotificationException extends NotificationException
{
    public function __construct(?Throwable $previous)
    {
        parent::__construct(
            message: __('notification.exception.failed_sms'),
            code: self::SMS_EXCEPTION_CODE,
            previous: $previous
        );
    }
}
