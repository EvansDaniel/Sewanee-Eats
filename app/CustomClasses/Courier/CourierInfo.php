<?php

namespace App\CustomClasses\Courier;

use App\CustomClasses\Availability\IsAvailable;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class CourierInfo
{
    protected $courier;
    protected $courier_info;

    public function __construct(User $courier)
    {
        if (!$courier->hasRole('courier')) {
            throw new InvalidArgumentException('The User type passed must have the role of courier');
        }
        $this->courier = $courier;
        $this->courier_info =
            \App\Models\CourierInfo::where('user_id', $courier->id)->firstOrFail();
    }

    /**
     * @return null|integer returns the CourierType constant for the
     * given courier for the current, ongoing shift
     *
     */
    public function getCourierTypeCurrentShift()
    {
        foreach ($this->courier->getAvailability() as $time_range) {
            // find the current shift and get the courier type b/c we
            // know that this user is on the that shift
            if (IsAvailable::nowIsBetweenOrEqualToTimeRange($time_range, 0)) {
                return $time_range->pivot->courier_type;
            }
        }
        return null;
    }

    /**
     * @return \App\Models\CourierInfo the base CourierInfo model
     */
    public function getInfo()
    {
        return $this->courier_info;
    }

    /**
     * @param $bool bool represents whether the user is delivering the
     * an order right now or not
     * @param $order_id integer the id of the order that the courier
     * is being assigned to right now
     */
    public function setIsDeliveringOrder($bool, int $order_id = -1)
    {
        if ($bool && $order_id == -1) {
            throw new InvalidArgumentException('$bool cannot be true and $order_id be null');
        }
        $this->courier_info->is_delivering_order = $bool;
        if ($bool) {
            $this->courier_info->current_order_id = $order_id;
        } else {
            // set current_order_id to invalid order
            $this->courier_info->current_order_id = null;
        }
        $this->persist();
    }

    public function persist()
    {
        $this->courier_info->save();
    }
}