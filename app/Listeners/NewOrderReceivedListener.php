<?php

namespace App\Listeners;

use App\Events\NewOrderReceived;
use Mail;

class NewOrderReceivedListener
{
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
        $managers = [
            'evansdb0@sewanee.edu', 'kandeta0@sewanee.edu', 'iradub0@sewanee.edu'
        ];
        $m_name = [
            'Daniel Evans', 'Tariro Kandemiri', 'Blaise Iradukunda'
        ];
        $order = $event->orderRequest;
        if ($event->orderRequest->paid_with_venmo) {
            for ($i = 0; $i < count($managers); $i++) {
                Mail::send('emails.new_venmo_order', compact('items', 'order'), function ($message) use ($managers, $m_name, $i) {
                    $message->from('sewaneeeats@gmail.com');
                    $message->to($m_name[$i], $managers[$i])->subject('New Venmo Order Request!');
                });
            }
        } else {
            foreach ($managers as $manager) {
                Mail::send('emails.new_order', compact('order'), function ($message) use ($manager) {
                    $message->from('sewaneeeats@gmail.com');
                    $message->to($manager, $manager)->subject('New Order Request!');
                });
            }
        }
        /*$user = User::findOrFail(\Auth::id());
        Mail::send('emails.new_order', compact('items'), function ($message) use ($user) {
            $message->from('sewaneeeats@gmail.com');
            $message->to('evansdb0@sewanee.edu', 'Daniel Evans')->subject('New Order Request!');
        });*/

        /**
         * Compile the list of menu items from within the $event->orderRequest object,
         * mapping each menu item to its restaurant i.e.
         *
         * $map[$event->orderRequest->menuItems[0]->restaurant->name] = $event->orderRequest->menuItems[0]
         *
         * Next compile a list of available couriers to send the order request email to
         *
         * Then send the email-with the order model, the above $map, and any other data injected into the emails.order_received view-
         * to all available couriers (if a courier is currently working on an order, they are not available????
         * (or send it to them anyway and they can do it when they are done if no one else has))
         *
         * OR I can just assign the order to one of the couriers, but then I would have to determine how to do so
         * fairly if and only if we plan to pay couriers based on the number of orders they deliver
         */
        \Log::info("event fired!!!!!!!!!!!!!!!!");
    }
}
