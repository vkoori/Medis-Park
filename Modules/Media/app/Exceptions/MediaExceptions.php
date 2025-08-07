<?php

namespace Modules\Media\Exceptions;

use App\Exceptions\HttpException;

class MediaExceptions
{
    public static function fileNotFound()
    {
        return new HttpException(
            statusCode: 404,
            messageBag: __('media::exceptions.media.file_not_found')
        );
    }

    public static function uploadFailed()
    {
        return new HttpException(
            statusCode: 500,
            messageBag: __('media::exceptions.media.upload_failed')
        );
    }
}
