<?php

namespace App\Http\Middleware;

use App\Models\Role;
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
    public function handle($request, Closure $next, $role)
    {
        // Role name is unique so we can use it this way
        $role_name = Role::where('name', 'admin')->first()->name;
        // redirect to home page if user is not an admin
        if (!Auth::check() || $role != $role_name) {
            return redirect()->route('home');
        }
        return $next($request);
    }
}
