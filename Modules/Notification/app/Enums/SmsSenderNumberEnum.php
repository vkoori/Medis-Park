<?php

namespace Modules\Notification\Enums;

use Modules\Notification\Services\SmsProviders\Kavenegar;

enum SmsSenderNumberEnum: string
{
    case KAVENEGAR = Kavenegar::class;

    /**
     * sort by priority
     *
     * @return array<SmsSenderNumberEnum>
     */
    public static function product(): array
    {
        return [
            self::KAVENEGAR
        ];
    }

    /**
     * sort by priority
     *
     * @return array<SmsSenderNumberEnum>
     */
    public static function advertising(): array
    {
        return [
            self::KAVENEGAR
        ];
    }

    public static function fallback(): SmsSenderNumberEnum
    {
        return self::KAVENEGAR;
    }
}
