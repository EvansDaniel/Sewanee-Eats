<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/19/17
 * Time: 11:45 AM
 */

namespace App\CustomClasses\ShoppingCart;


abstract class PaymentType
{
    const STRIPE_PAYMENT = 0;
    const VENMO_PAYMENT = 1;
}