<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpException extends \Exception
{
    protected array $messageBag = [];

    public function __construct(
        protected int $statusCode,
        string|array $messageBag,
        int $code = HttpExceptionCodes::UNHANDLED_ERROR_CODE,
        ?\Throwable $previous = null
    ) {
        $message = Response::$statusTexts[$statusCode];
        if (is_string($messageBag)) {
            $message = $messageBag;
        }
        if (is_array($messageBag)) {
            $this->messageBag = $messageBag;
        }

        parent::__construct(message: $message, code: $code, previous: $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessageBag(): array
    {
        return $this->messageBag;
    }
}
