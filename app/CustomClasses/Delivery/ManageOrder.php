<?php

namespace App\CustomClasses\Delivery;

use App\Models\Order;
use App\User;

class ManageOrder
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function assignToOrder(User $user)
    {

    }

    public function refundOrder()
    {
        $this->order->was_refunded = true;
        $this->order->is_cancelled = true;
        $this->order->save();
    }

    public function refundItem($item_id)
    {
        //TODO: delete the item from the menu item orders listing
    }

    public function cancellationStatus($bool)
    {
        $this->order->is_cancelled = $bool;
        $this->order->save();
    }

    public function deliveredStatus($bool)
    {
        $this->order->is_delivered = $bool;
        $this->order->save();
    }

    public function processingStatus($bool)
    {
        $this->order->is_being_processed = $bool;
        $this->order->save();
    }

    public function paidForStatus($bool)
    {
        $this->order->is_paid_for = $bool;
        $this->
    }
}