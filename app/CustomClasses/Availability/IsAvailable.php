<?php

namespace App\CustomClasses\Availability;

use App\Contracts\Availability;
use App\Models\TimeRange;
use Carbon\Carbon;

/**
 * Class IsAvailable determines the availability of the resource passed as object
 * Caller is responsible for using the correct availability method for the
 * object passed to the constructor
 * @package App\CustomClasses\Availability
 */
class IsAvailable
{
    // TODO: we will need to check restaurant availability every time
    // TODO: a user does something associated with a restaurant b/c
    // TODO: it can change at any time, a good message will need to be given
    // TODO: in this event
    protected $resource;

    /**
     * IsAvailable constructor.
     * @param Availability $resource Can be menu items, restaurants, couriers
     * Any object passed must implement the the Availability interface
     */
    public function __construct(Availability $resource)
    {
        // this assumes that all classes that implement availability have
        // the same types for there collections of resources, which is logically the case
        $this->resource = $resource;
    }

    public static function isWithinTimeRange(TimeRange $time_range, TimeRange $within)
    {
        // get start and end times for both shifts
        $start_carbon1 = $time_range->getStartCarbon();
        $end_carbon1 = $time_range->getEndCarbon();
        $start_carbon2 = $within->getStartCarbon();
        $end_carbon2 = $within->getEndCarbon();
        // zero out all the shifts seconds, which we don't care about for the time range
        // this is just to ensure equality of Carbon objects below for day, hour, and min
        $start_carbon1->second(0);
        $start_carbon2->second(0);
        $end_carbon1->second(0);
        $end_carbon2->second(0);
        $start_is_within = ($start_carbon2->between($start_carbon1, $end_carbon1)
            || $start_carbon2->equalTo($start_carbon1));
        $end_is_within = ($end_carbon2->between($start_carbon1, $end_carbon1)
            || $end_carbon2->equalTo($end_carbon1));
        return $end_is_within && $start_is_within;
    }

    /**
     * @param $spare_time_before_ending integer the amount of extra time needed before the resource is unavailable
     * @return bool
     */
    public function isAvailableNow($spare_time_before_ending = 0)
    {
        // this should return TimeRange object(s)
        $resource_time_ranges = $this->resource->getAvailability();
        if (empty($resource_time_ranges)) { //
            return false;
        }
        // getAvailability() returns single TimeRange (not array) for weekly specials
        if ($resource_time_ranges instanceof TimeRange) {
            return IsAvailable::nowIsBetweenOrEqualToTimeRange($resource_time_ranges, $this->resource->getExtraTime());
        } else { // other time range types return an array of TimeRange
            // check all time ranges and determine if now is between them
            foreach ($resource_time_ranges as $time_range) {
                if (IsAvailable::nowIsBetweenOrEqualToTimeRange($time_range, $this->resource->getExtraTime())) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function nowIsBetweenOrEqualToTimeRange(TimeRange $time_range, $spare_time_before_ending)
    {
        if (empty($time_range)) {
            return false;
        }
        $start_carbon = $time_range->getStartCarbon();
        $end_carbon = $time_range->getEndCarbon();
        // TODO: check if Carbon::now() is equal to start or end carbon
        return Carbon::now()->between($start_carbon, $end_carbon, true) &&
            Carbon::now()->diffInMinutes($end_carbon) >= $spare_time_before_ending;
    }
}