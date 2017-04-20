<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 7:01 PM
 */

namespace App\Http\Controllers\Admin\OrderRelated;


class OrderCalculation
{
    protected $orders;

    /**
     * OrderCalculation constructor.
     * @param \Illuminate\Database\Eloquent\Collection|static[] $orders
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function avg(string $attribute)
    {
        $total = $this->sum($attribute);
        return round($total / count($this->orders), 3);
    }

    public function sum(string $attribute)
    {
        $total = 0;
        foreach ($this->orders as $order) {
            $total += $order->orderPriceInfo->{$attribute};
        }
        return round($total, 3);
    }
}