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
    protected $resource;
    /**
     * IsAvailable constructor.
     * @param Availability $resource Can be menu items, restaurants, couriers
     * Any object passed must implement the the Availability interface
     */
    public function __construct(Availability $resource)
    {
        // this assumes that all classes that implement availability have
        // the same types for there collections of resources, which is logically they case
        $this->resource = $resource;
    }

    /**
     * @param $spare_time integer the amount of extra time needed before the resource is unavailable
     * @return bool
     */
    public function isAvailableNow($spare_time = 0)
    {
        $resource_time_ranges = $this->resource->getAvailability(); // this should return TimeRange objects
        $now = Carbon::now();
        $today_dow = $now->format('l');
        foreach ($resource_time_ranges as $time_range) {
            if ($this->timeRangeIsForToday($time_range)) {
                $start_carbon = $this->createStartCarbonCourier($time_range);
                $end_carbon = $this->createEndCarbonCourier($time_range, $today_dow);
                return Carbon::now()->between($start_carbon, $end_carbon) &&
                    Carbon::now()->diffInMinutes($end_carbon) >= $spare_time;
            }
        }
        return false;
    }

    /**
     * @param TimeRange $time_range the time range to test if it indicates today
     * @return bool returns true if the end_dow or start_dow matches today's dow
     */
    private function timeRangeIsForToday(TimeRange $time_range)
    {
        // if either the start_dow or the end_dow is the same as today's dow
        // then $time_range specifies a time range that is potentially today's shift
        $start_dow = $time_range->start_dow;
        $end_dow = $time_range->end_dow;
        return Carbon::now()->format('l') == $start_dow
            || Carbon::now()->format('l') == $end_dow;
    }

    private function createStartCarbonCourier(TimeRange $time_range)
    {
        $start_dow = $time_range->start_dow;
        $end_dow = $time_range->end_dow;
        $start_carbon = Carbon::now();
        // if today is the end dow, make the start day be tomorrow
        if (Carbon::now()->format('l') != $start_dow
            && Carbon::now()->format('l') == $end_dow
        ) {
            $start_carbon = new Carbon('yesterday');
        }
        $start_carbon->hour($time_range->start_hour);
        $start_carbon->minute($time_range->start_min);
        return $start_carbon;
    }

    private function createEndCarbonCourier(TimeRange $time_range, $today_dow)
    {
        $end_carbon = $time_range->end_dow == $today_dow
            ? Carbon::now() : new Carbon('next ' . $time_range->end_dow);
        $end_carbon->hour($time_range->end_hour);
        $end_carbon->minute($time_range->end_min);
        return $end_carbon;
    }

    public function restaurantIsAvailable($spare_time = 0)
    {
        $resource_time_ranges = $this->resource->getAvailability();
        \Log::info($resource_time_ranges);
    }
}