<?php

use Illuminate\Http\Request;
use App\Exceptions\HttpException;
use App\Utils\Response\ErrorFacade;
use App\Exceptions\HttpExceptionCodes;
use Illuminate\Foundation\Application;
use App\Utils\Exceptions\ExceptionUtil;
use App\Http\Middleware\RawBodyMiddleware;
use Laravel\Octane\Exceptions\DdException;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Laravel\Octane\Exceptions\TaskException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Middleware\NormalizeInputMiddleware;
use Vkoori\JwtAuth\Exceptions\BaseGuardException;
use Vkoori\JwtAuth\Middlewares\JwtScopeMiddleware;
use App\Http\Middleware\PutPatchFormDataMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Vkoori\LaravelJwt\Exceptions\NonVerifyJwtException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            SetLocaleMiddleware::class,
            RawBodyMiddleware::class,
            PutPatchFormDataMiddleware::class,
            NormalizeInputMiddleware::class,
        ])->alias([
            'jwt.scope' => JwtScopeMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            $innerException = $e->getPrevious();

            return match (true) {
                $innerException instanceof DdException => $innerException->render(),
                $e instanceof ModelNotFoundException || (
                    $e instanceof TaskException
                    && is_a(
                        object_or_class: $e->getClass(),
                        class: ModelNotFoundException::class,
                        allow_string: true
                    )
                ) => ErrorFacade::notFound(
                    message: __('exception.model_not_found'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null,
                    code: HttpExceptionCodes::THROTTLE_REQUESTS
                ),
                $e instanceof ThrottleRequestsException => ErrorFacade::customError(
                    statusCode: 429,
                    message: __('exception.throttle_requests'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null,
                    code: HttpExceptionCodes::THROTTLE_REQUESTS
                ),
                $e instanceof AuthenticationException => ErrorFacade::unauthorized(
                    message: __('exception.unauthenticated'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null,
                    code: HttpExceptionCodes::THROTTLE_REQUESTS
                ),
                $e instanceof ValidationException => ErrorFacade::unprocessableEntity(
                    message: __('exception.invalid_request'),
                    errors: $e->errors(),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null,
                    code: HttpExceptionCodes::THROTTLE_REQUESTS
                ),
                $e instanceof NonVerifyJwtException => ErrorFacade::unauthorized(
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                $e instanceof UnauthorizedException => ErrorFacade::unauthorized(
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                $e instanceof BaseGuardException => ErrorFacade::unauthorized(
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                $e instanceof HttpException => ErrorFacade::customError(
                    statusCode: $e->getStatusCode(),
                    message: $e->getMessage(),
                    errors: $e->getMessageBag(),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null,
                    code: $e->getCode()
                ),
                $e instanceof HttpExceptionInterface => ErrorFacade::customError(
                    statusCode: $e->getStatusCode(),
                    message: __('exception.server_error'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                default => ErrorFacade::serverError(
                    message: __('exception.server_error'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
            };
        });
    })->create();
