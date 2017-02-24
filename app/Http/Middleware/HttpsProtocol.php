<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && env('APP_ENV') === 'production') {
            return $next($request);
            //return redirect()->secure($request->getRequestUri());
        }
    }
}