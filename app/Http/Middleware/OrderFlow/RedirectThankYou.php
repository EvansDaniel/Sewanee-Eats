<?php

namespace App\Http\Middleware\OrderFlow;

use Closure;
use Session;

class RedirectThankYou
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
        // redirect user to home if they were not REFERRED from the checkout page
        // this means that the only way to get to this URL is if the application takes them to it
        // meaning they will only be sent there if they just checked out
        if ($request->headers->get('referer') != route('checkout')) {
            return redirect()->route('home');
        }
        Session::reflash();
        \Log::info(Session::get('weekly_special_order'));
        return $next($request);
    }
}
