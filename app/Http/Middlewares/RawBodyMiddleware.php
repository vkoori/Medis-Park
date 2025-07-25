<?php

namespace App\Http\Middlewares;

use Illuminate\Http\Request;

class RawBodyMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        $rawBody = file_get_contents('php://input');
        $rawBody = json_decode($rawBody, true);

        if (is_array($rawBody)) {
            $request->merge($rawBody);
        }

        return $next($request);
    }
}
