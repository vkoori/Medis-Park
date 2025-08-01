<?php

namespace App\Utils\Response;

use Illuminate\Support\Facades\Facade;

/**
 * @method static StandardResponse badRequest(array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 * @method static StandardResponse notFound(array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 * @method static StandardResponse unauthorized(array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 * @method static StandardResponse unprocessableEntity(array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 * @method static StandardResponse serverError(array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 * @method static StandardResponse customError(int $statusCode, array|string $message = null, array|string $errors = [], ?array $debug = null, int $code = 0)
 *
 * @see Errors
 */
class ErrorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'error_utils';
    }
}
