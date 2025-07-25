<?php

namespace App\Http\Middlewares;

use Closure;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultLocale = 'fa';
        $supportedLocales = ['en', 'fa'];
        $currentLocale = $request->headers->get('Accept-Language', $defaultLocale);

        if (!in_array($currentLocale, $supportedLocales)) {
            $currentLocale = $defaultLocale;
        }

        app()->setLocale($currentLocale);

        return $next($request);
    }
}
