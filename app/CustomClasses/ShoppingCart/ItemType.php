<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/12/17
 * Time: 7:07 PM
 */

namespace App\CustomClasses\ShoppingCart;


abstract class ItemType
{
    const EVENT_ITEM = 0;
    const RESTAURANT_ITEM = 1;
    const CATERING_ITEM = 2;
}