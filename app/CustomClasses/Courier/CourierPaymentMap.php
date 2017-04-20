<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 8:27 PM
 */

namespace App\CustomClasses\Courier;


use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class CourierPaymentMap
{

    /**
     * @var User
     */
    private $user;
    private $payment;

    public function __construct(User $user, $payment)
    {
        if (!$user->hasRole('courier')) {
            throw new InvalidArgumentException('$user must have role of courier');
        }
        if (!is_numeric($payment)) {
            throw new InvalidArgumentException('$payment must be a numeric value, given -> ' . $payment);
        }
        $this->user = $user;
        $this->payment = $payment;
    }

    /**
     * @return User
     */
    public function getCourier(): User
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }


}