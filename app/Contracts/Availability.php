<?php

namespace App\Contracts;

use App\Models\TimeRange;

interface Availability
{
    // returns a common data structure with information about when the
    // object is available to be used
    /**
     * @return array|TimeRange
     */
    public function getAvailability();
}