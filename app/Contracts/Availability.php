<?php

namespace App\Contracts;

interface Availability
{
    // returns a common data structure with information about when the
    // object is available to be used
    public function getAvailability();
}