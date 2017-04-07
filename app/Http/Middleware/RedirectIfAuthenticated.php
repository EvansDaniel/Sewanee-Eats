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
        // and if you are a user snooping around where you shouldn't be you will be
        // redirected to the home page
        if (Auth::guard($guard)->check() && !Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
