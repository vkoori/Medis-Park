<?php

namespace Modules\Notification\Services\SmsProviders;

interface SmsProviderInterface
{
    public function send(string|array $receptor, string $message);
}
