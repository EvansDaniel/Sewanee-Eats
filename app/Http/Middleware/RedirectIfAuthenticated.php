<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if you are an admin, you won't be redirected
        if (Auth::guard($guard)->check() && !Auth::user()->hasRole('admin')) {
            return redirect('/home');
        }

        return $next($request);
    }
}
