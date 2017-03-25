<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/25/17
 * Time: 4:15 PM
 */

namespace App\CustomClasses\Availability;


abstract class TimeRangeType
{
    // these types are stored with time ranges that fit the classification
    const WEEKLY_SPECIAL = 0;
    const SHIFT = 1;
    const ON_DEMAND = 2;
    const MENU_ITEM = 3;
}