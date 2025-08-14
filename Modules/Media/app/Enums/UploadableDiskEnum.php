<?php

namespace Modules\Media\Enums;

use App\Utils\Enum\EnumContract;
use App\Exceptions\NotImplementedException;

enum UploadableDiskEnum: string
{
    use EnumContract;

    // case S3_PUBLIC = 's3_public';
    case S3_PRIVATE = 's3_private';

    public static function public(): array
    {
        return [
            // self::S3_PUBLIC,
        ];
    }

    public static function private(): array
    {
        return [
            self::S3_PRIVATE,
        ];
    }

    public function disk(): string
    {
        return match ($this) {
            // self::S3_PUBLIC => 's3_public',
            self::S3_PRIVATE => 's3_private',
            default => throw new NotImplementedException()
        };
    }
}
