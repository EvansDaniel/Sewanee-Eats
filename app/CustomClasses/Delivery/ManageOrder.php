<?php

namespace App\CustomClasses\Delivery;

use App\CustomClasses\Courier\CourierInfo;
use App\Models\CourierOrder;
use App\Models\Order;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

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

    public function processingStatus(bool $bool)
    {
        $this->order->is_being_processed = $bool;
        $this->order->save();
    }

    public function refundOrder(bool $bool)
    {
        if ($this->order->is_cancelled) {
            throw new InvalidArgumentException('Can\'t refund a cancelled order in ' . __FUNCTION__);
        }
        // Update pricing info
        $this->order->was_refunded = $bool;
        // remove courier if we refund the order
        if ($bool) { // if we are refunding the order
            $this->removeAssignedCourier();
        }
        $this->order->save();
    }

    public function removeAssignedCourier()
    {
        // delete the courier order if there is a courier assigned
        $courier_order = CourierOrder::where('order_id', $this->order->id)->first();
        if (!empty($courier_order)) { // this shouldn't be empty, but just a sanity check
            $courier_order->delete();
        }
    }

    /**
     * @param $item_id integer id of item to refund
     * @param int $refund_amount float the amount to
     * refund to the customer, if nothing passed this value
     * will default to the current cost of the item
     */
    public function refundItem(int $item_id, int $refund_amount = 0)
    {
        // get the cost of food via master pricing class
        // then subtract the price of this item
    }

    public function cancellationStatus(bool $bool)
    {
        $this->order->is_cancelled = $bool;
        $this->deliveredStatus(false);
        $this->processingStatus(false);
        if ($bool) { // if we are cancelling the order
            $this->removeAssignedCourier();
        }
        $this->paidForStatus(false);
        $this->order->save();
    }

    public function deliveredStatus(bool $bool)
    {
        $this->order->is_delivered = $bool;
        if ($bool) { // if is delivered, set it to not being processed
            $this->processingStatus(false);
        }
        $this->order->save();
    }

    public function paidForStatus($bool)
    {
        $this->order->is_paid_for = $bool;
        $this->order->save();
    }
}