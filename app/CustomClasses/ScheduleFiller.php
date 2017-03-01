<?php

namespace App\CustomClasses;

use App\Models\Role;
use App\User;
use Auth;
use Carbon\Carbon;

class ScheduleFiller
{
    private $day_of_week_map;
    private $timezone;
    private $times;
    private $times_length;
    private $count_couriers;
    private $courier_work_slots;

    public function __construct()
    {
        $this->timezone = "America/Chicago";
        $this->times = [7, 9, 11, 13, 15, 17, 19, 21, 23, 1];
        $this->day_of_week_map = $this->makeDayOfWeekMap();
        $this->count_couriers = $this->makeCountOfOnlineCouriersArray();
        $this->courier_work_slots = $this->daysCourierWorksArray();
        $this->times_length = count($this->times);
    }

    /**
     * @return array an array that maps the Carbon::now()->dayOfWeek
     * attribute onto the strings for each day
     * example: [0 => 'Monday',1 => "Tuesday",...,6 => "Sunday"]
     * NOTE: the first element of the array will be determined by the
     * the day of week TODAY; the rest of the elements will be the following
     * 6 days
     */
    private function makeDayOfWeekMap()
    {

        //$num = 1; // debugging
        $today = Carbon::now($this->timezone);//->addHours(24*$num);

        // $today->hour will return 17 if time is
        // between 5:00 PM and 5:59 PM
        // a new day for the schedule only arises
        // when it is passed 2AM on the current day
        // if today's hour is between 12AM and 2AM
        if ($today->hour >= 0 &&
            $today->hour < 2
        ) {
            // pretend it is still the previous day until it is 2AM
            // on the current day, thus if it is actually sunday at 1AM
            // the schedule will still show Saturday as the first day at the top
            // until 2AM on Sunday
            $today = $today->subDay();
        }
        $days_of_week = [];
        for ($i = 0; $i < 7; $i++) {
            $days_of_week[$today->dayOfWeek] = $today->format('l');
            $today = $today->addDay();
        }
        return $days_of_week;
    }

    /**
     * @return array an array that stores the number of couriers working
     * for each day and for each time slot
     */
    private function makeCountOfOnlineCouriersArray()
    {
        $count_couriers = [];
        $all_couriers = Role::where('name', 'courier')->first()->users;
        foreach ($this->day_of_week_map as $day_number => $day_string) {
            foreach ($this->times as $time) {
                $count_couriers[$day_number][$time] =
                    $this->getCountOnlineCouriersForDayTime
                    ($all_couriers, $day_number, $time);
            }
        }
        return $count_couriers;
    }


    private function getCountOnlineCouriersForDayTime($couriers, $day, $time)
    {
        $num_available_couriers = 0;
        // create time of the specified hour
        $time = Carbon::createFromTime($time, 0, 0, $this->timezone);
        foreach ($couriers as $courier) {
            if ($courier->isAvailable($day, $time)) {
                $num_available_couriers++;
            }
        }
        return $num_available_couriers;
    }

    /**
     * @return array an array that stores truth values
     * about whether the authenticated courier works on a given day and given time
     */
    private function daysCourierWorksArray()
    {
        $courier = User::find(Auth::id());
        $time_slots_courier_courier_is_working = [];
        foreach ($this->day_of_week_map as $day_number => $day_string) {
            foreach ($this->times as $time) {
                $carbon_time = Carbon::createFromTime($time, 0, 0, $this->timezone);
                $time_slots_courier_courier_is_working[$day_number][$time] =
                    $courier->isAvailable($day_number, $carbon_time);
            }
        }
        return $time_slots_courier_courier_is_working;
    }

    /**
     * Used internally to produce an array that will store the number
     * of couriers working for each day and for each time slot
     * @param $couriers array the couriers for which you wish to determine
     * how many of them are working on a given day and time
     * @param $day integer the day you wish to check
     * @param $time integer the time you wish to check
     * @return int the number of couriers working on the given day and time
     */
    // TODO: abstract this to IsAvailable with method name "filterAvailableObjects($objects,$day,$time)", then we could just count() it
    /**
     * @param $day_index integer the day_index into the $this->getDaysOfWeek() array
     * on which you wish to retrieve the number of couriers working
     * @param $time_index integer the time_index into the $this->getTimes() array
     * at which you wish to retrieve the number of couriers working
     * @return integer the number of couriers working on the given
     * day at the given time
     * (NOTE: the args are actually indexes used to retrieve the day and time)
     */
    public function numCouriersOnDayAtTime($day_index, $time_index)
    {
        return $this->getCountCouriers()[$day_index]
        [$this->getTimes()[$time_index]];
    }

    public function getCountCouriers()
    {
        return $this->count_couriers;
    }

    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @param $day_index integer the day_index into the $this->getDaysOfWeek() array
     * on which you want to check if the user works
     * @param $time_index integer the time_index into the $this->getTimes() array
     * at which you want to check if the user works
     * @return boolean true if the courier works on the given day
     * and at the given time, false otherwise
     * (NOTE: the args are actually indexes used to retrieve the day and time)
     */
    public function userWorksOnDayAtTime($day_index, $time_index)
    {
        return $this->getCourierWorkSlots()[$day_index]
        [$this->getTimes()[$time_index]];
    }

    public function getCourierWorkSlots()
    {
        return $this->courier_work_slots;
    }

    public function getDaysOfWeek()
    {
        return $this->day_of_week_map;
    }

}