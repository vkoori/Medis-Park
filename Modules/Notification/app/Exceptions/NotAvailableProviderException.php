<?php

namespace Modules\Notification\Exceptions;

class NotAvailableProviderException extends NotificationException
{
    public function __construct()
    {
        parent::__construct(
            message: __('notification.exception.not_available_provider'),
            code: self::NOT_AVAILABLE_PROVIDER
        );
    }
}
