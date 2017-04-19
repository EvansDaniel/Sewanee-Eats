<?php

namespace App\CustomClasses\Delivery;

use App\CustomClasses\Courier\CourierInfo;
use App\Models\CourierOrder;
use App\Models\Order;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;

class ManageOrder
{
    protected $order;
    protected $courier;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->courier = $this->order->getCourier();
    }

    public function assignToOrder(User $user)
    {
        if (!empty($this->courier)) {
            throw new InvalidArgumentException('Courier already assigned to order, remove the courier first');
        }
        $this->courier = $user;
        // call master pricing func
        $courier_order = new CourierOrder;
        // determine courier payment
        $courier_order->courier_payment =
            DeliveryInfo::getMaxRestaurantCourierPayment($this->order);

        // set up the courier order relation to know this courier did this order
        $courier_order->order_id = $this->order->id;
        $courier_order->courier_id = $user->id;
        $courier_order->save();
    }

    public function setDeliveringNow()
    {
        if (empty($this->courier)) {
            throw new RuntimeException('No courier has been assigned to the order, assign a courier first via $this->assignToOrder(User)');
        }
        // order is being processed now
        $this->processingStatus(true);
        $courier_info = new CourierInfo($this->courier);
        $courier_info->setIsDeliveringOrder(true, $this->order->id);
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
        $this->order->couriers()->detach();
        $this->courier = null; // null out the courier object for this OrderManger
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