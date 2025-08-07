<?php

namespace Modules\Media\Enums;

use App\Exceptions\NotImplementedException;
use App\Utils\Enum\EnumContract;

enum UploadableDiskEnum: string
{
    use EnumContract;

    case S3_PUBLIC = 's3_public';
    case S3_PRIVATE = 's3_private';

    public function disk(): string
    {
        return match ($this) {
            self::S3_PUBLIC => 's3_public',
            self::S3_PRIVATE => 's3_private',
            default => throw new NotImplementedException()
        };
    }
}
