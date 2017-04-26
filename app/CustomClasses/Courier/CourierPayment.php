<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 8:21 PM
 */

namespace App\CustomClasses\Courier;


use App\CustomClasses\Stats\TimeFrames;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class CourierPayment
{
    protected $couriers;
    protected $payment_summary;
    protected $time_frame;

    public function __construct($couriers, $time_frame)
    {
        $this->couriers = $couriers;
        $this->time_frame = $time_frame;
    }

    /**
     * @return float total amount to pay all workers (couriers) within the $this->time_frame
     */
    public function getTotalPayment()
    {
        $payment_summary = $this->getPaymentSummary();
        $sum = 0;
        foreach ($payment_summary as $ps) {
            $sum += $ps->getPayment();
        }
        return round($sum, 3);
    }

    public function getPaymentSummary()
    {
        if (!empty($this->payment_summary)) {
            return $this->payment_summary;
        }

        $payment_summary = [];
        foreach ($this->couriers as $courier) {
            $payment_summary[] = new CourierPaymentMap($courier, $this->sumPayment($courier));
        }
        return $this->payment_summary = $payment_summary;
    }

    public function sumPayment(User $user)
    {
        if (!$user->hasRole('courier')) {
            throw new InvalidArgumentException('$user must have the role of courier');
        }
        $user_orders = TimeFrames::getCollection($user->orders, $this->time_frame);
        $payment = 0;
        foreach ($user_orders as $order) {
            $payment += $order->pivot->courier_payment;
        }
        return $payment;
    }
}