<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/27/17
 * Time: 12:44 AM
 */

namespace App\Contracts;


interface ResourceTimeRange
{
    public function getResourceTimeRangesByDay($dow);

    public function getTimeRangeType();
}