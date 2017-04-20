<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 9:20 PM
 */

namespace App\CustomClasses\Stats;


use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class TimeFrames
{
    public static $TWO_WEEKS_AGO = 'Last two weeks';
    public static $ONE_MONTH_AGO = 'Last month';
    public static $MAX = 'Max';
    protected $named_time_frame;

    public function __construct()
    {
    }

    public static function getCollection($collection, $time_frame)
    {
        if (empty($collection)) return $collection;
        try {
            $start = TimeFrames::parseTimeFrame($time_frame);
        } catch (InvalidArgumentException $e) {
            throw $e;
        }
        $now = Carbon::now();
        $remaining = [];
        foreach ($collection as $item) {
            if (TimeFrames::inBetween($item, $start, $now)) {
                $remaining[] = $item;
            }
        }
        return $remaining;
    }

    private static function parseTimeFrame($time_frame)
    {
        if (is_string($time_frame)) {
            try {
                return TimeFrames::parseStringTimeFrame($time_frame);
            } catch (InvalidArgumentException $e) {
                throw $e;
            }
        } else { // custom time frame instance
            // TODO: parse the time frame instance
        }
    }

    private static function parseStringTimeFrame($time_frame)
    {
        if ($time_frame == TimeFrames::$MAX) {
            return Carbon::now()->subYears(50)->hour(0)->minute(0);
        } else if ($time_frame == TimeFrames::$ONE_MONTH_AGO) {
            return Carbon::now()->subMonth()->hour(0)->minute(0);
        } else if ($time_frame == TimeFrames::$TWO_WEEKS_AGO) {
            return Carbon::now()->subWeeks(2)->hour(0)->minute(0);
        }
        throw new InvalidArgumentException('$time_frame should be one of the static TimeFrames variables');
    }

    private static function inBetween($has_created_at_property, $start, $now)
    {
        if (empty($has_created_at_property->created_at)) {
            throw new InvalidArgumentException('$has_created_at_property must have a non-empty created_at property');
        }
        $created_at = new Carbon($has_created_at_property->created_at);
        return $created_at->between($start, $now);
    }

    public static function getNamedTimeFrames()
    {
        return [TimeFrames::$TWO_WEEKS_AGO, TimeFrames::$ONE_MONTH_AGO, TimeFrames::$MAX];
    }
}