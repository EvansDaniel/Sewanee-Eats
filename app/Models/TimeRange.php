<?php

namespace App\Models;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class TimeRange extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\TimeRange',
            'time_ranges_users', 'time_range_id', 'user_id');

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
}
