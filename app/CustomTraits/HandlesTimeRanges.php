<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/26/17
 * Time: 10:24 PM
 */

namespace App\CustomTraits;

use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

trait HandlesTimeRanges
{
    // TODO: validate input
    public function timeRangeSetup(TimeRange $time_range, Request $request, $time_range_type)
    {
        $time_range->start_dow = $request->input('start_dow');
        $time_range->start_hour = $request->input('start_hour');
        $time_range->start_min = $request->input('start_min');
        $time_range->end_dow = $request->input('end_dow');
        $time_range->end_hour = $request->input('end_hour');
        $time_range->end_min = $request->input('end_min');
        // this controller creates shifts and no other type of time range
        $time_range->time_range_type = $time_range_type;

        return $time_range;
    }

    /**
     * @param $shift Shift
     * determines if the shift object represents a valid time range
     * @return integer
     * -1 if the $this->shift is not disjoint with all other shifts
     * -2 if the $this->shift has invalid start and end times
     * -3 if an invalid start_dow or end_dow was given
     */
    public function validShift(Shift $shift)
    {
        if ($shift->shift == null) {
            throw new InvalidArgumentException('No TimeRange passed or null TimeRange passed');
        }
        $valid_start_end_times_msg = $this->hasValidStartAndEndTimes($shift->shift);
        if (!empty($valid_start_end_times_msg)) {
            return $valid_start_end_times_msg;
        }
        foreach ($shift->getCurrentShifts() as $current_shift) {
            // we need to check if the two objects are the same
            // b/c this same validation will be used on updates
            if ($current_shift->getId() == $shift->getId()) continue;
            if (!$this->timeRangesDisjoint($shift->shift, $current_shift->shift)) {
                return 'The shift given is not disjoint with other existing shifts'; // all shifts must be disjoint
            }
        }
        return '';
    }

    /**
     * Used by Shift
     * @param TimeRange $time_range
     * @return int|string
     */
    private function hasValidStartAndEndTimes(TimeRange $time_range)
    {
        $start_index = $this->findDayOfWeek($time_range->start_dow);
        $end_index = $this->findDayOfWeek($time_range->end_dow);
        // if either day of week was not found, return false
        if ($start_index == -1 || $end_index == -1) {
            return 'Invalid start or end day given';
        }
        $general_msg = 'Invalid day and time start and end ranges
                or start day of week is not within one day of end day of week';
        // make sure that the day of the week is within 1 day of each
        // other AND that the start dow comes before the end dow
        // !($this->shortestDistSecondFromFirst($time_range->end_dow,$time_range->start_dow) <= 1
        // $time_range->getEndCarbon()->diffInDays($time_range->getStartCarbon()) > 1
        if (!($this->shortestDistSecondFromFirst($time_range->end_dow, $time_range->start_dow) <= 1)) {
            return $general_msg;
        }
        if (!$this->startTimesComeAfterEndTimes($time_range)) {
            return 'Invalid day and time start and end ranges
                or start day of week is not within one day of end day of week';
        }
        return 0;
    }

    public function findDayOfWeek($day_of_week)
    {
        for ($i = 0; $i < count($this->getDayOfWeekNames()); $i++) {
            if ($day_of_week == $this->getDayOfWeekNames()[$i])
                return $i;
        }
        return -1;
    }

    public function getDayOfWeekNames()
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];
    }

    /**
     * @param $day_start string the day to get the shortest distance from
     * @param $day string the day to determine how far away it is from $day_start
     * @return int the shortest amount of days between $day_start and $day
     */
    public function shortestDistSecondFromFirst($day_start, $day)
    {
        $start_index = $this->findDayOfWeek($day_start);
        $end_index = $this->findDayOfWeek($day);
        if ($start_index == -1 || $end_index == -1) {
            return -1;
        }
        return min(Carbon::DAYS_PER_WEEK - $end_index + $start_index, abs($end_index - $start_index));
    }

    /**
     * Determines if the start time given within $time_range comes
     * before the end time given by $time_range
     * @param TimeRange $time_range
     * @return int|string if $time_range represents invalid
     * start and end times, a message to the user is returned
     * other wise zero is returned
     */
    private function startTimesComeAfterEndTimes(TimeRange $time_range)
    {
        $same_dow = $time_range->start_dow == $time_range->end_dow;
        if ($same_dow &&
            $time_range->start_hour > $time_range->end_hour
        ) {
            return false;
        }
        $same_hour = $time_range->start_hour == $time_range->end_hour;
        if ($same_dow && $same_hour && $time_range->start_min >= $time_range->end_min) {
            return false;
        }
        return true;
    }

    /**
     * Precondition: $this->startTimesComeAfterEndTimes() returns 0 for the $time_range1
     * and $time_range2 i.e. the time ranges given are valid time ranges
     * @param TimeRange $time_range1
     * @param TimeRange $time_range2
     * @return bool returns true if the shifts share no overlap between start and end times whatsoever
     */
    public function timeRangesDisjoint(TimeRange $time_range1, TimeRange $time_range2)
    {
        // get start and end times for both shifts
        $start_carbon1 = $time_range1->getStartCarbon();
        $end_carbon1 = $time_range1->getEndCarbon();
        $start_carbon2 = $time_range2->getStartCarbon();
        $end_carbon2 = $time_range2->getEndCarbon();
        // created shift
        //\Log::info($start_carbon1);
        //\Log::info($end_carbon1);
        // checking this shift
        //\Log::info($start_carbon2);
        //\Log::info($time_range2->start_dow . ' ' . $time_range2->end_dow);
        //\Log::info($end_carbon2);
        // shifts disjoint if it is not the case that the start time of shift1
        // is not between both the start and end time of shift2 AND the end time of shift1
        // is not between both the start and end time of shift2
        // and vice versa for shift2
        // NOTE: the end times can be the same as start times
        // as well as start times be the same as end times
        $shift1_disjoint =
            ($start_carbon1->equalTo($end_carbon2) || !$start_carbon1->between($start_carbon2, $end_carbon2)) &&
            ($end_carbon1->equalTo($start_carbon2) || !$end_carbon1->between($start_carbon2, $end_carbon2));
        //\Log::info($shift1_disjoint);
        // start_carbon2 equal to end_carbon1 or not between the other shifts start and end times
        $shift2_disjoint = ($start_carbon2->equalTo($end_carbon1) || !$start_carbon2->between($start_carbon1, $end_carbon1)) &&
            ($end_carbon2->equalTo($start_carbon1) || !$end_carbon2->between($start_carbon1, $end_carbon1));
        //\Log::info($shift2_disjoint);
        return $shift1_disjoint && $shift2_disjoint;
    }

    public function isValidTimeRangeForRestaurant(Restaurant $r, TimeRange $time_range)
    {
        if (!$this->startTimesComeAfterEndTimes($time_range)) {
            return 'Invalid day and time start and end ranges';
        }
        // get existing restaurant open times and determine if $time_range overlaps
        // we only need to check this for on demand
        if ($r->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
            foreach ($r->getAvailability() as $existing_r_time_range) {
                // we need this check b/c on updates to the passed time range
                // the $time_range will be in the list we loop through
                if ($existing_r_time_range->id != $time_range->id) {
                    if (!$this->timeRangesDisjoint($existing_r_time_range, $time_range)) {
                        return 'The new open time must be disjoint (no overlap) with other restaurant open times';
                    }
                }
            }
        }
        return 0;
    }

    /**
     * @param $dow string one of the elements provided by getDayOfWeekNames()
     * @param $time_range_type integer A constant in the TimeRangeType class
     * @param $resource_id_name string The name of the resource id in the DB for this resource
     * @return
     */
    public function getTimeRangesByDay($dow, $time_range_type, $resource_id_name = null)
    {
        // need to order time ranges in chronological order
        // shifts are completely disjoint (this is enforced on creation of shifts)
        // so a shift starts and ends prior to the beginning of another shift
        // thus it is sufficient to order by start_hour to achieve chronological order
        $param_array = [];
        if ($resource_id_name == null) {
            $param_array = [
                'time_range_type' => $time_range_type,
                'start_dow' => $dow
            ];
        } else {
            $param_array = [
                'time_range_type' => $time_range_type,
                'start_dow' => $dow,
                $resource_id_name => $this->id
            ];
        }
        $time_ranges = TimeRange::where($param_array)
            ->orderBy('start_hour', 'ASC')
            ->orderBy('start_min', 'ASC')->get();
        return $time_ranges;
    }

    public function distanceFromDay($day_distance_from, $day)
    {
        //\Log::info('day dist ' . $day_distance_from);
        //\Log::info('day ' . $day);
        $start_day_index = $this->findDayOfWeek($day_distance_from);
        if ($start_day_index != -1) { // day was found
            // loop through days behind start day
            for ($i = 0; $i < $start_day_index; $i++) {
                if ($this->getDayOfWeekNames()[$i] == $day) {
                    return -($start_day_index - $i);
                }
            }
            for ($i = $start_day_index; $i < Carbon::DAYS_PER_WEEK; $i++) {
                if ($this->getDayOfWeekNames()[$i] == $day) {
                    return $i - $start_day_index;
                }
            }
        }
        return -1;
    }
}