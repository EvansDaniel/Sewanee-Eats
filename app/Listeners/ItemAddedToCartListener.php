<?php

namespace App\Listeners;

use App\CustomClasses\Advertisements\OnDemandAdvertisement;
use App\Events\ItemAddedToCart;

class ItemAddedToCartListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ItemAddedToCart $event
     * @return void
     */
    public function handle(ItemAddedToCart $event)
    {
        $ad = OnDemandAdvertisement::withCart($event->cart, $event->added_items);
        \Session::flash('status_good', $ad->getAdvertisementMsg());
    }
}
