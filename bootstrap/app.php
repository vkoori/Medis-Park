<?php

use App\Http\Middlewares\NormalizeInputMiddleware;
use App\Http\Middlewares\RawBodyMiddleware;
use App\Http\Middlewares\SetLocaleMiddleware;
use App\Utils\Exceptions\ExceptionUtil;
use App\Utils\Response\ErrorFacade;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Vkoori\JwtAuth\Exceptions\BaseGuardException;
use Vkoori\JwtAuth\Middlewares\JwtScopeMiddleware;
use Vkoori\LaravelJwt\Exceptions\NonVerifyJwtException;

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
            NormalizeInputMiddleware::class,
        ])->alias([
            'jwt.scope' => JwtScopeMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            return match (true) {
                $e instanceof NonVerifyJwtException => ErrorFacade::customError(
                    statusCode: 401,
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                $e instanceof UnauthorizedException => ErrorFacade::customError(
                    statusCode: 401,
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                $e instanceof BaseGuardException => ErrorFacade::customError(
                    statusCode: 401,
                    message: __('exception.access_token_invalid'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
                default => ErrorFacade::serverError(
                    message: __('exception.server_error'),
                    debug: config('app.debug') ? ExceptionUtil::convertExceptionToArray($e) : null
                ),
            };
        });
    })->create();
