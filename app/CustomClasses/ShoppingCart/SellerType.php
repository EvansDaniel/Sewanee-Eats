<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/12/17
 * Time: 9:53 PM
 */

namespace App\CustomClasses\ShoppingCart;


abstract class SellerType
{
    const ON_DEMAND = 0;
    const WEEKLY_SPECIAL = 1;
    const EVENT = 2;
}