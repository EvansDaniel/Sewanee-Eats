<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/26/17
 * Time: 10:24 PM
 */

namespace App\CustomTraits;

use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\MenuItem;
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

    public function copyRestTimeRangesToMenuItems(Restaurant $rest)
    {
        if (empty($rest)) {
            return;
        }
        $time_ranges = $rest->getAvailability();
        $items = $rest->menuItems;
        foreach ($items as $item) {
            foreach ($time_ranges as $time_range) {
                $copy = $this->copyTimeRange($time_range);
                if (empty($err_msg = $this->isValidTimeRangeForMenuItem($item, $copy))) { // if valid time range
                    // we need to convert the time range type from restaurant to menu item
                    $copy->time_range_type = TimeRangeType::MENU_ITEM;
                    // and give it the menu item's id
                    $copy->menu_item_id = $item->id;
                    $copy->save();
                }
            }
        }
    }

    /**
     * @param TimeRange $time_range the time range to copy
     * @return TimeRange a deep copy of the time range passed as argument
     * @throws InvalidArgumentException
     */
    public function copyTimeRange(TimeRange $time_range)
    {
        if (empty($time_range)) {
            throw new InvalidArgumentException('$time_range is empty');
        }
        $copy = new TimeRange;
        $copy->start_dow = $time_range->start_dow;
        $copy->start_hour = $time_range->start_hour;
        $copy->start_min = $time_range->start_min;
        $copy->end_dow = $time_range->end_dow;
        $copy->end_hour = $time_range->end_hour;
        $copy->end_min = $time_range->end_min;
        // this controller creates shifts and no other type of time range
        $copy->time_range_type = $time_range->time_range_type;
        return $copy;
    }

    /**
     * @param MenuItem $menu_item
     * @param TimeRange $time_range
     * @return int|string returns error msg to user when given invalid
     * time range for a menu item, 0 when given a valid time range
     * Note: this doesn't check if the menu item is within the time range
     * specified by the restaurant it belongs to
     */
    public function isValidTimeRangeForMenuItem(MenuItem $menu_item, TimeRange $time_range)
    {
        if (!$this->startTimesComeAfterEndTimes($time_range)) {
            return 'Invalid day and time start and end ranges';
        }
        /*if (!$this->isWithinRestaurantTimeRange($menu_item, $time_range)) {
            return 'Menu item time range must be within restaurant time range';
        }*/
        // get existing restaurant open times and determine if $time_range overlaps
        // we only need to check this for on demand
        foreach ($menu_item->getAvailability() as $existing_m_time_range) {
            // we need this check b/c on updates to the passed time range
            // the $time_range will be in the list we loop through
            if ($existing_m_time_range->id != $time_range->id) {
                if (!$this->timeRangesDisjoint($existing_m_time_range, $time_range)) {
                    return 'The new availability time range must be disjoint (no overlap) with other restaurant open times';
                }
            }
        }
        return 0;
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
        // zero out all the shifts seconds, which we don't care about for the time range
        // this is just to ensure equality of Carbon objects below for day, hour, and min
        $start_carbon1->second(0);
        $start_carbon2->second(0);
        $end_carbon1->second(0);
        $end_carbon2->second(0);
        // shifts disjoint if it is not the case that the start time of shift1
        // is not between both the start and end time of shift2 AND the end time of shift1
        // is not between both the start and end time of shift2
        // and vice versa for shift2
        // NOTE: the end times can be the same as start times
        // as well as start times be the same as end times
        $shift1_disjoint =
            ($start_carbon1->equalTo($end_carbon2) || !$start_carbon1->between($start_carbon2, $end_carbon2)) &&
            ($end_carbon1->equalTo($start_carbon2) || !$end_carbon1->between($start_carbon2, $end_carbon2));
        //\Log::info('1 ' . $shift1_disjoint);
        // start_carbon2 equal to end_carbon1 or not between the other shifts start and end times
        $shift2_disjoint = ($start_carbon2->equalTo($end_carbon1) || !$start_carbon2->between($start_carbon1, $end_carbon1)) &&
            ($end_carbon2->equalTo($start_carbon1) || !$end_carbon2->between($start_carbon1, $end_carbon1));
        //\Log::info('2 ' . $shift2_disjoint);
        return $shift1_disjoint && $shift2_disjoint;
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
        // commented out so we can overlap shifts
        /*foreach ($shift->getCurrentShifts() as $current_shift) {
            // we need to check if the two objects are the same
            // b/c this same validation will be used on updates
            if ($current_shift->getId() == $shift->getId()) continue;
            if (!$this->timeRangesDisjoint($shift->shift, $current_shift->shift)) {
                return 'The shift given is not disjoint with other existing shifts'; // all shifts must be disjoint
            }
        }*/
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

    public function onDemandNotAvailableMsg()
    {
        $time_till_next_shift = Shift::diffBetweenNowAndNextShift();
        $next_shift = Shift::next();
        $msg = "Sorry we are closed right now";
        if ($time_till_next_shift < 60) { // If open in less than an hour
            $msg .= ", but we will be open in " . $time_till_next_shift . " minutes! ";
        } elseif ($time_till_next_shift <= 3 * 60) { // IF we are open in 3 hours
            $msg .= ", but we will be open in a couple hours!";
        } elseif (!empty($next_shift)) { //-- If we have another shift -->
            $msg .= ", but we will be open " . $this->getDisplayTextOfStartDay($next_shift) . "!";
        } else {
            $msg .= ", but we will be open again soon!";
        }
        return $msg . " In the mean time, feel free to browse our menus!";
    }

    public function getDisplayTextOfStartDay(TimeRange $time_range)
    {
        if (Carbon::today()->format('l') == $time_range->getStartCarbon()->format('l')) {
            return "later today";
        }
        if (Carbon::tomorrow()->format('l') == $time_range->getStartCarbon()->format('l')) {
            return "tomorrow";
        } else {
            return "next " . $time_range->getStartCarbon()->format('l');
        }
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
    public function getTimeRangesByDay(string $dow, $time_range_type, $resource_id_name = null)
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

    private function isWithinRestaurantTimeRange(MenuItem $menu_item, TimeRange $within)
    {
        $rest = $menu_item->restaurant;
        if ($rest->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL)) {
            return true; // menu item selling times don't matter for weekly specials
        }
        foreach ($rest->getAvailability() as $time_range) {
            if (IsAvailable::isWithinTimeRange($time_range, $within)) {
                return true;
            }
        }
        return false;
    }
}