<?php

namespace App\Http\Middleware;

use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use Closure;

class RedirectNotOpen
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
        $cart = new ShoppingCart();
        if (!$cart->hasSpecialItems() && !Shift::onDemandIsAvailable()) {
            \Session::forget('cart');
            return redirect()->route('list_restaurants')->with('status_good', 'Sorry we recently closed!');
        }
        return $next($request);
    }
}
