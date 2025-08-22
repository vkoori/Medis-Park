<?php

namespace Modules\Product\Exceptions;

use App\Exceptions\HttpException;

class ProductExceptions
{
    public static function notFound()
    {
        return new HttpException(
            statusCode: 404,
            messageBag: __('product::exceptions.not_found')
        );
    }
}
