<?php

namespace App\CustomClasses\ShoppingCart;


abstract class RestaurantOrderCategory
{
    const ON_DEMAND = 0;
    // weekly specials doesn't have to be just weekly
    const WEEKLY_SPECIAL = 1;
    const EVENT = 2;
}