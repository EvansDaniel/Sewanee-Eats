<?php

namespace App\Listeners;

use App\Events\NewOrderReceived;
use App\Jobs\SendOrderRequestEmails;
use Illuminate\Foundation\Bus\DispatchesJobs;

class NewOrderReceivedListener
{
    use DispatchesJobs;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  NewOrderReceived $event wrapper for the Order model, which contains all
     * the details of the order that was received
     * @return void
     */
    public function handle(NewOrderReceived $event)
    {
        $this->dispatch((new SendOrderRequestEmails($event->orderRequest)));
    }
}
