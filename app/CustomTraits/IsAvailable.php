<?php

namespace App\CustomTraits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait IsAvailable
{
    /**
     * @param $object mixed This object must have an attribute
     * available_times which is a 2D-array of times (in the form "hh:mm-hh:mm")
     * for each day that denote when $object is available for a certain day
     * @return bool true if the $object is available
     */
    public function isAvailable($object)
    {
        // all time ranges must look like this hh:mm-hh:mm
        $available_times = json_decode($object->available_times, true);
        $day = Carbon::now()->dayOfWeek - 1;
        foreach ($available_times[$day] as $available_time) {
            if ($this->isInRange($available_time)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $available_time string in the form "hh:mm-hh:mm", which denotes
     * a range of time an object (restaurant, menu item, courier, etc) is available
     * @return bool true if the current time (Central Time) is within the
     * range specified by $available_time
     */
    private function isInRange($available_time)
    {
        // means that this object is not available on this day
        if ($available_time == "closed" || !$available_time) {
            return false;
        }
        $timezone = 'America/Kentucky/Louisville';
        $current_hour = Carbon::now($timezone)->hour - 1;
        $current_min = Carbon::now($timezone)->minute;

        // get the time today as central time zone
        // parse time range that ultimately came from the DB
        $time_range = explode("-", $available_time);
        $start = explode(":", $time_range[0]);
        $finish = explode(":", $time_range[1]);

        $start_hour = $start[0];
        $start_min = $start[1];
        $finish_hour = $finish[0];
        $finish_min = $finish[1];

        if ($current_hour >= $finish_hour || $current_hour < $start_hour) {
            return false;
        }

        // restaurant opens at 11:30 and it is 11:00 right now, we need to check
        // the start_min only if start_hour == current_hour
        if ($current_hour == $start_hour) {
            if ($current_min < $start_min) {
                return false;
            }
        }

        // hour is in the range, so check minutes only if we are in the
        // last hour of availability
        if ($finish_hour - 1 == $current_hour) {
            if (($current_min + $this->getCushionPeriod()) >= $finish_min) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return int the amount of time needed for a courier to
     * reach the restaurant or for a menu item to be available
     * if the restaurant is close to closing or the menu item
     * is close to not be sold at this time
     */
    private function getCushionPeriod()
    {
        // courier needs at least 15 minutes to get to restaurant to order
        // this item before the closing period
        return 15;
    }

    /**
     * @param Request $request request must contain name attributes
     * that are contained within either the update_available_times partial
     * or the create_available_times partial
     * @return string json string of the $array_of_times array
     * with the available times normalized (no spaces in them)
     */
    public function createAvailableTimesJsonStringFromRequest(Request $request)
    {
        $mon = $request->input('monday');
        $tues = $request->input('tuesday');
        $wed = $request->input('wednesday');
        $thurs = $request->input('thursday');
        $fri = $request->input('friday');
        $sat = $request->input('saturday');
        $sun = $request->input('sunday');
        $hours_open = [$mon, $tues, $wed, $thurs, $fri, $sat, $sun];
        return $this->createAvailableTimesJsonString($hours_open);
    }

    /**
     * @param $array_of_times array a 2D array that is an array of times
     * for which some object is available for each day of the week
     * @return string json string of the $array_of_times array
     * with the available times normalized (no spaces in them)
     */
    public function createAvailableTimesJsonString($array_of_times)
    {
        $array_of_times = $this->normalizeHoursOpen($array_of_times);
        return json_encode($array_of_times);
    }

    /**
     * @param $hours_open array expects the available_times attribute
     * on models that have this attribute
     * @return mixed all times contained the the available_times attribute
     * of the model where all spaces are removed
     */
    private function normalizeHoursOpen($hours_open)
    {
        $num_days = count($hours_open);
        for ($day = 0; $day < $num_days; $day++) {
            $num_available_times = count($hours_open[$day]);
            for ($time = 0; $time < $num_available_times; $time++) {
                $hours_open[$day][$time] = str_replace(" ", "", $hours_open[$day][$time]);
            }
        }
        return $hours_open;
    }

    private function pr($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}