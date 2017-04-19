<?php

namespace App\Events;

use App\CustomClasses\ShoppingCart\ShoppingCart;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemAddedToCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cart;
    public $added_items;

    /**
     * Create a new event instance.
     * @param $cart ShoppingCart the current session's shopping cart
     * @param
     * @return void
     */
    public function __construct(ShoppingCart $cart, $added_items = null)
    {
        $this->cart = $cart;
        $this->added_items = $added_items;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
