<?php

namespace Modules\Post\Exceptions;

use App\Exceptions\HttpException;

class PostExceptions
{
    public static function notFound()
    {
        return new HttpException(
            statusCode: 404,
            messageBag: __('post::exceptions.post_not_found')
        );
    }
}
