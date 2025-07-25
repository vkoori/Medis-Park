<?php

namespace App\Exceptions;

class NotImplementedException extends HttpException
{
    public function __construct(string $message = 'Not implemented!')
    {
        parent::__construct(
            statusCode: 500,
            messageBag: $message
        );
    }
}
