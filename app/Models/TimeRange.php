<?php

namespace App\Models;

use App\CustomTraits\HandlesTimeRanges;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class TimeRange extends Model
{
    use HandlesTimeRanges;

    public function users()
    {
        // need courier type to determine courier type for shift
        return $this->belongsToMany('App\User',
            'time_ranges_users', 'time_range_id', 'user_id')
            ->withPivot('courier_type');
    }

    public function isTimeRangeType($type)
    {
        return $this->getTimeRangeType() == $type;
    }

    public function getTimeRangeType()
    {
        return $this->time_range_type;
    }

    public function scopeOfType($query, $type)
    {
        if (!is_int($type)) {
            throw new InvalidArgumentException('The type passed is not an integer. Use the one of the constants in TimeRangeType class');
        }
        return $query->where('time_range_type', $type);
    }

    public function getDayDateTimeString()
    {
        $start_carbon = $this->getStartCarbon();
        $end_carbon = $this->getEndCarbon();
        return $start_carbon->toDayDateTimeString() . ' - ' . $end_carbon->toDayDateTimeString();
    }

    // todo: allow spare time (in minutes) to be added by addMinutes() Carbon method

    /**
     * @return Carbon
     */
    public function getStartCarbon()
    {
        $end_carbon = $this->getEndCarbon();
        $start_carbon = $end_carbon;
        $dist = $this->distanceFromDay($this->end_dow, $this->start_dow);
        if ($dist < 0) {
            $start_carbon->subDays(abs($dist));
        } else if ($dist > 0) {
            $start_carbon->subDays(Carbon::DAYS_PER_WEEK - $dist);
        }
        // if the above if statements didn't run it means that $dist == 0
        // i.e. $this->end_dow and $this->start_dow are equal (same day)
        $start_carbon->minute($this->start_min);
        $start_carbon->hour($this->start_hour);
        return $start_carbon;
    }

    public function getEndCarbon()
    {
        return $this->endCarbon(Carbon::now());
    }

    public function endCarbon(Carbon $carbon)
    {
        $end_carbon = null;
        if ($carbon->format('l') == $this->end_dow &&
            $carbon->hour <= $this->end_hour
        ) {
            if ($carbon->hour == $this->end_hour && $carbon->minute > $this->end_min) {
                $end_carbon = new Carbon('next ' . $this->end_dow);
            } else {
                $end_carbon = $carbon;
            }
        } else {
            // different day then end_dow or same day but it's passed the end_hour
            $end_carbon = new Carbon('next ' . $this->end_dow);
        }
        $end_carbon->hour($this->end_hour);
        $end_carbon->minute($this->end_min);
        return $end_carbon;
    }
}
