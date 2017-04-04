<?php

namespace App\Mail;

use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderToManager extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        $on_demand_order_type = RestaurantOrderCategory::ON_DEMAND;
        $weekly_order_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        $venmo_payment_type = PaymentType::VENMO_PAYMENT;
        return $this->view('emails.new_order_to_manager',
            compact('order', 'on_demand_order_type', 'weekly_order_type', 'venmo_payment_type'));
    }
}
