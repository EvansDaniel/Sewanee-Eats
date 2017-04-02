<?php

namespace App\CustomClasses\Delivery;

use App\CustomClasses\Courier\CourierInfo;
use App\Models\CourierOrder;
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
        // call master pricing func
        $courier_order = new CourierOrder;
        $courier_order->courier_payment =
            DeliveryInfo::getMaxRestaurantCourierPayment($this->order);
        $courier_order->order_id = $this->order->id;
        $courier_order->courier_id = $user->id;
        // order is being processed
        $this->processingStatus(true);
        $courier_info = new CourierInfo($user);
        $courier_info->setIsDeliveringOrder(true, $this->order->id);
        $courier_order->save();
    }

    public function processingStatus($bool)
    {
        $this->order->is_being_processed = $bool;
        $this->order->save();
    }

    public function refundOrder()
    {
        // Update pricing info
        $this->order->was_refunded = true;
        $this->order->is_cancelled = true;
        $this->order->save();
    }

    /**
     * @param $item_id integer id of item to refund
     * @param int $refund_amount float the amount to
     * refund to the customer, if nothing passed this value
     * will default to the current cost of the item
     */
    public function refundItem($item_id, $refund_amount = 0)
    {
        // get the cost of food via master pricing class
        // then subtract the price of this item
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

    public function paidForStatus($bool)
    {
        $this->order->is_paid_for = $bool;
        $this->order->save();
    }
}