<?php

namespace App\Http\Middleware;

use App\User;
use Auth;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check())
            return redirect()->route('home');

        $user = User::find(Auth::id());
        // loop through roles and check if the user has the role
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }
        return redirect()->route('home');
    }
}
