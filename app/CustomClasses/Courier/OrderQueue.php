<?php

namespace App\CustomClasses\Courier;


use App\CustomClasses\Delivery\ManageOrder;
use App\CustomTraits\FiltersOrders;
use App\Models\Order;
use App\User;
use Auth;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class OrderQueue
{
    protected $courier;
    protected $courier_info;
    protected $orders_for_courier;
    protected $orders;

    use FiltersOrders;

    public function __construct(User $courier = null)
    {
        if (!empty($courier) && !$courier->hasRole('courier')) {
            throw new InvalidArgumentException('The User type passed must have the role of courier');
        }
        $this->courier = $courier;
        $this->courier_info = new CourierInfo($this->courier);
        // check they are on shift, etc.
        if (!empty($err_msg = $this->validateCourier())) {
            throw new InvalidArgumentException($err_msg);
        }
        // $this->orders and $this->orders_for_courier set in retrieveOrders()
        $this->retrieveOrders();
    }

    /**
     * Determines if the courier attempting to view the next order in the
     * queue is allowed to view/accept the order
     * Necessary b/c we are emailing the courier a link that takes them
     * to the next order, which can lead to stale links in the emails
     * so we need to check for this stuff
     * @return string|integer returns a string as a message to user on
     * failure and zero on success
     */
    public function validateCourier()
    {
        if (!$this->courier->isOnShift()) {
            return "You are not on the current shift and 
                    therefore cannot view the orders queue";
        }
        // check that the courier isn't currently delivering an order
        $courier_info = $this->courier_info->getInfo();
        if ($courier_info->is_delivering_order) {
            return "You are currently delivering an order. Please finish this one first or mark it as complete at the bottom
                    of the page";
        }
        return 0;
    }

    /**
     * Retrieves the orders that are currently
     * undelivered and not begin processed starting from oldest
     * to newest and only the orders that fit his/her courier type
     * who is trying to view retrieve the orders via the order queue
     */
    private function retrieveOrders()
    {
        // order oldest to newest and only undelivered and
        // not being processed orders
        $potential_orders = Order::pending()->
        orderBy('created_at', 'ASC')->get();
        // filter to on demand orders
        $this->orders = $this->onDemandOrders($potential_orders);
        $courier_type = $this->courier_info->getCourierTypeCurrentShift();
        // filter to orders of this courier type
        $this->orders_for_courier = $this->ordersOfCourierType($this->orders, $courier_type);
    }

    /**
     * In the event that a courier is unable to fulfill an order
     * after accepting it, this method will be used to return the
     * order to the order queue, where it will be made a top priority
     * @param $order Order the order to be reinserted into the queue
     * at top priority
     */
    public static function reinsertOrder(Order $order)
    {
        if ($order->is_delivered) {
            throw new InvalidArgumentException('The order given has been delivered already');
        }
        // check that the order is currently being delivered
        // if so, set it to not being delivered
        // TODO: check that the order is being delivered by the given courier before reinserting
        $order_manager = new ManageOrder($order);
        $order_manager->processingStatus(false);
        // nothing to do if the order is not being delivered and isnt' delivered
    }

    public function getOrderQueue()
    {
        if (!Auth::user()->hasRole('manager') && !Auth::user()->hasRole('admin')) {
            throw new InvalidArgumentException('You do not have privileges to access this information. Contact your manager or an admin');
        }
        $potential_orders = Order::pending()->orderBy('created_at', 'ASC')->get();
        $on_demand_orders = $this->onDemandOrders($potential_orders);
        return $on_demand_orders;
    }

    /**
     * @return bool true if there are orders
     * that haven't been serviced and are not being serviced
     * (i.e. pending), false otherwise
     */
    public function hasOrders()
    {
        return $this->numberOfOrdersPending() > 0;
    }

    /**
     * @return int the number of orders that are pending
     * unqualified by the given courier (the overall total pending orders)
     */
    public function numberOfOrdersPending()
    {
        return count($this->orders);
    }

    /**
     * This method will be used to retrieve and assign the next
     * order in the queue to the given courier
     * @return Order|null the next order out of the queue
     * or null if the queue is empty
     */
    public function nextOrder()
    {
        if (!$this->hasOrdersForCourierType()) {
            return null;
        }
        return $this->orders_for_courier[0];
    }

    /**
     * @return bool true if there are orders
     * THAT CAN BE SERVICED BY THE GIVEN COURIER and
     * that haven't been serviced and are not being serviced
     * (i.e. pending), false otherwise
     */
    public function hasOrdersForCourierType()
    {
        return $this->numberOfOrdersPendingForCourier() > 0;
    }

    /**
     * @return int the number of orders that are pending
     * qualified by the given courier
     * (number of orders the given courier can service at this time)
     */
    public function numberOfOrdersPendingForCourier()
    {
        return count($this->orders_for_courier);
    }

    /**
     * @return mixed retrieves all pending orders
     */
    public function getPendingOrders()
    {
        return $this->orders;
    }

    /**
     * @return mixed retrieves the pending orders that can be serviced
     * by the given courier
     */
    public function getPendingOrdersForCourier()
    {
        return $this->orders_for_courier;
    }

    /**
     * Sends an email to the manager of the current shift
     * in the event that one of the orders is not accepted
     * by a courier within some amount of time
     */
    public function orderNotServicedPromptly()
    {

    }
}