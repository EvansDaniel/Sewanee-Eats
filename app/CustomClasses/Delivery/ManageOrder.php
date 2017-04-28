<?php

namespace App\CustomClasses\Delivery;

use App\Models\Order;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class ManageOrder
{
    protected $order;
    protected $courier_for_order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        if ($this->order->is_delivered) {
            $this->courier_for_order = $this->order->getCourier();
        } else {
            $this->courier_for_order = $this->order->getCurrentCourier();
        }
    }

    /**
     * Assigns the courier to the order "permanently"
     * which means the relationship b/w the courier and the order
     * is stored in the couriers_order, along with the courier's payment
     * for delivering the order
     * @param User $user to assign the order to
     */
    public function permanentlyAssignToOrder(User $user)
    {

    }

    public function assignToOrder(User $user)
    {
        if (!empty($this->courier_for_order)) {
            throw new InvalidArgumentException('Courier already assigned to order, remove the courier first');
        }
        // attach the courier to the current orders
        $user->currentOrders()->attach($this->order->id);
        // update this instance's knowledge of $this->order's deliverer
        $this->courier_for_order = $user;
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

    public function refundOrder(bool $bool)
    {
        if ($this->order->is_cancelled) {
            throw new InvalidArgumentException('Can\'t refund a cancelled order in ' . __FUNCTION__);
        }
        $this->order->was_refunded = $bool;
        // remove courier if we are refunding the order
        // and the order is not delivered
        if ($bool && !$this->order->is_delivered) {
            $this->removeAssignedCourier();
        }
        // TODO: Update pricing info, set profit to be the the -(total_price - cost_of_food) for refunded orders where the courier is not removed
        $this->order->save();
    }

    /**
     * If the order is delivered, remove the courier
     * stored in the couriers_orders table
     * Otherwise, remove the current courier (the one that
     * is delivering it right now)
     * @return User the courier that was previously assigned to the order
     */
    public function removeAssignedCourier()
    {
        if ($this->order->is_delivered) {
            $this->order->courier()->detach();
        } else {
            $this->order->currentCourier()->detach();
        }
        $courier = $this->courier_for_order;
        // null out the courier object for this OrderManger
        $this->courier_for_order = null;
        return $courier;
    }

    public function removePermanentCourier()
    {
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

    public function processingStatus(bool $bool)
    {
        $this->order->is_being_processed = $bool;
        $this->order->save();
    }

    public function paidForStatus($bool)
    {
        $this->order->is_paid_for = $bool;
        $this->order->save();
    }
}