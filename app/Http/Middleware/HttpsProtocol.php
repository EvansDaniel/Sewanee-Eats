<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Request::header('x-forwarded-proto') != 'https' && !$request->secure() && env('APP_ENV') === 'production') {

            if (\URL::previous() === 'http://172.31.31.209') {
                \Log::info('it worked');
                return $next($request);
            }
            \Log::info(\URL::previous());
            $request->setTrustedProxies([$request->getClientIp()]);
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}