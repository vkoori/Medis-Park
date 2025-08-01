<?php

namespace App\Utils\Response;

use Symfony\Component\HttpFoundation\Response;

class Errors
{
    public function badRequest(
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: Response::HTTP_BAD_REQUEST,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }

    public function notFound(
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: Response::HTTP_NOT_FOUND,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }

    public function unauthorized(
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: Response::HTTP_UNAUTHORIZED,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }

    public function unprocessableEntity(
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }

    public function serverError(
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: Response::HTTP_INTERNAL_SERVER_ERROR,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }

    public function customError(
        int $statusCode,
        array|string $message = null,
        array|string $errors = [],
        ?array $debug = null,
        int $code = 0
    ): StandardResponse {
        return new StandardResponse(
            statusCode: $statusCode,
            message: $message,
            data: $errors,
            debug: $debug,
            code: $code
        );
    }
}
