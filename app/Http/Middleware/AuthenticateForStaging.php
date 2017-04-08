<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateForStaging
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
        if (!\Auth::check() && env('APP_ENV') === "staging") {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
