<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderRequest;

    /**
     * Create a new event instance.
     * @param $orderRequest Order The order model associated with the order just placed
     * @return void
     */
    public function __construct(Order $orderRequest)
    {
        $this->orderRequest = $orderRequest;
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
