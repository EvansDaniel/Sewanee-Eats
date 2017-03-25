<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/25/17
 * Time: 1:03 AM
 */

namespace App\CustomClasses\Schedule;


use App\CustomClasses\Availability\TimeRangeType;
use App\Models\TimeRange;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class Shift
{
    protected $day_of_week_values;
    protected $shift;

    public function __construct(TimeRange $shift = null)
    {
        $this->day_of_week_values = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];
        $this->shift = $shift;
    }

    public function getCurrentShifts()
    {
        $shift_time_ranges = TimeRange::where('time_range_type', TimeRangeType::SHIFT)->get();
        $shifts = [];
        foreach ($shift_time_ranges as $shift_time_range) {
            $shifts[] = new Shift($shift_time_range);
        }
        return $shifts;
    }

    public function __toString()
    {
        return $this->shift->__toString();
    }

    // prints the internal TimeRange

    public function validShiftCreation()
    {
        if ($this->shift == null) {
            throw new InvalidArgumentException('No TimeRange passed or null TimeRange passed');
        }
        $start_index = $this->findDayOfWeek($this->shift->start_dow);
        $end_index = $this->findDayOfWeek($this->shift->end_dow);
        // if either day of week was not found, return false
        if ($start_index == -1 || $end_index == -1) {
            return false;
        }
        // make sure that the day of the week is within 1 day of each
        // other AND that the start dow comes before the end dow
        return abs($start_index - $end_index) <= 1 &&
            $start_index <= $end_index;
    }

    private function findDayOfWeek($day_of_week)
    {
        for ($i = 0; $i < count($this->getDayOfWeekNames()); $i++) {
            if ($day_of_week == $this->getDayOfWeekNames()[$i])
                return $i;
        }
        return -1;
    }

    public function getDayOfWeekNames()
    {
        return $this->day_of_week_values;
    }

    public function getDateTimeString()
    {
        $start_carbon = null;
        if (Carbon::now()->format('l') == $this->shift->start_dow) {
            $start_carbon = Carbon::now();
        } else {
            $start_carbon = new Carbon('next ' . $this->shift->start_dow);
        }
        $start_carbon->hour($this->shift->start_hour);
        $start_carbon->minute($this->shift->start_min);
        if (Carbon::now()->format('l') == $this->shift->end_dow) {
            $end_carbon = Carbon::now();
        } else {
            $end_carbon = new Carbon('next ' . $this->shift->end_dow);
        }
        $end_carbon->hour($this->shift->end_hour);
        $end_carbon->minute($this->shift->end_min);
        return $start_carbon . ' - ' . $end_carbon;
    }

    public function getCouriers()
    {
        return $this->shift->users;
    }

    public function getStartDay()
    {
        return $this->shift->start_day;
    }

    public function getEndDay()
    {
        return $this->shift->end_dow;
    }

    public function getStartMin()
    {
        return $this->shift->start_min;
    }

    public function getEndMin()
    {
        return $this->shift->end_min;
    }

    public function getStartHour()
    {
        return $this->shift->start_hour;
    }

    public function getEndHour()
    {
        return $this->shift->end_hour;
    }

    public function getId()
    {
        return $this->shift->id;
    }
}