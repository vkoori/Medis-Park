<?php

namespace App\Utils;

class CacheKeys
{
    public static function smsProviderError(string $className): string
    {
        return "sms:error:{$className}";
    }
}
