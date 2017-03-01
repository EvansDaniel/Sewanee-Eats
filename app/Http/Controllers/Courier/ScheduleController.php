<?php

namespace App\Http\Controllers\Courier;

use App\CustomTraits\IsAvailable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use IsAvailable;

    protected $start_hour_of_first_shift;

    public function __construct()
    {
        $this->start_hour_of_first_shift = 6;
    }

    public function addCourierToTimeSlot(Request $request)
    {
        // TODO: if user is already added, do nothing and send warning
        $user = User::find(Auth::id());
        $times = json_decode($user->available_times, true);
        $day = $request->input('day');
        $hour = $request->input('hour');
        $time_to_add = $this->convertTimeToDBTime($hour);
        if ($this->isAvailableOnDayAtTime($user, $day,
            Carbon::createFromTime($hour, 0, 0, $this->tz()))
        ) {
            return back()->with('status_bad', 'You have already added yourself to that time slot');
        }
        if ($this->shiftPassedAlready($day, $hour)) {
            return back()->with('status_bad', 'This shift has already passed');
        }

        //\Log::info($times[$day]); // debugging
        $times[$day][] = $time_to_add;
        $user->available_times = json_encode($times);
        $user->save();
        return back()->with('status_good', 'Your schedule has been updated');
    }

    /**
     * Precondition: $hour MUST represent the hour directly in between
     * the shift's start and end hour. Right now the time slots are 2 hours
     * IF THIS CHANGES, this will need to be updated
     * @param $hour integer the hour directly in between the shift
     * start and end
     * @return string
     */
    private function convertTimeToDBTime($hour)
    {
        // the diff between the $hour and the
        // shift's start and end hours
        $shiftDiff = 1;
        if ($hour < 9) {
            return "0" . ($hour - $shiftDiff) . ":00-0" . ($hour + $shiftDiff) . ":00";
        } else if ($hour == 9) {
            return "0" . ($hour - $shiftDiff) . ":00-" . ($hour + $shiftDiff) . ":00";
        } else if ($hour == 23) { // we will use "00:00" to represent 12AM rather than "24:00"
            return ($hour - $shiftDiff) . ":00-" . "00:00";
        } else {
            return ($hour - $shiftDiff) . ":00-" . ($hour + $shiftDiff) . ":00";
        }
    }

    // time is an integer that is directly in between
    // the time slot is designates i.e. 7 -> 06:00-08:00

    private function shiftPassedAlready($day, $hour)
    {
        $now = Carbon::now($this->tz());
        $shift = Carbon::now($this->tz());
        if ($day >= $now->dayOfWeek) { // day of week is within this week
            $shift->addDays($day - $now->dayOfWeek);
        } else { // the day of week went into next week
            $shift->addDays(7 - ($now->dayOfWeek - $day));
        }
        // if we went to the next day (only this if statement is true)
        if ($hour < $this->start_hour_of_first_shift) {
            $shift->addDay();
        }
        $shift->setTime($hour, 0, 0);
        return $now->greaterThanOrEqualTo($shift);
    }

    public function removeCourierFromTimeSlot(Request $request)
    {
        // TODO: courier cannot remove him/herself if his/her shift is within
        // TODO: 24 hours
        $user = User::find(Auth::id());
        $day = $request->input('day');
        $hour = $request->input('hour');

        // check if courier is available for this time slot
        if (!$this->isAvailableOnDayAtTime($user, $day,
            Carbon::createFromTime($hour, 0, 0, $this->tz()))
        ) {
            return back()->with('status_bad',
                'You are currently not scheduled for this time slot');
        }

        if ($this->shiftPassedAlready($day, $hour - 1)) {
            return back()->with('status_bad', 'Can\'t remove shift because 
                it has already passed');
        }

        // check if shift is within ~24 hours from now. Can't remove if so
        if ($this->shiftWithin24Hours($day, $hour - 1)) {
            return back()->with('status_bad',
                'Your shift is within 24 hours, and cannot be removed. 
                 Contact one of the admins if you cannot work your shift');
        }

        $time = $this->convertTimeToDBTime($hour);
        //\Log::info("hour " . $hour);
        //\Log::info("time " . $time);
        $times = json_decode($user->available_times, true);
        // find $time index
        $i = 0;
        //\Log::info($times[$day]);
        while ($time != $times[$day][$i]) {
            //\Log::info($i);
            $i++;
        }
        unset($times[$day][$i]);
        $times[$day] = array_values($times[$day]);
        $user->available_times = json_encode($times);
        $user->save();
        return back()->with('status_good', 'You have been removed from that time slot');
    }

    private function shiftWithin24Hours($day, $hour)
    {
        $now = Carbon::now($this->tz());
        $shift = Carbon::now($this->tz());
        // this condition implies that the $day has gone into next week,
        // which is obviously not within 24 hours of now
        if ($day < $now->dayOfWeek) {
            return false;
        }
        $shift->addDays($day - $now->dayOfWeek);
        // if we went to the next day (only this if statement is true)
        if ($hour < $this->start_hour_of_first_shift) {
            $shift->addDay();
        }
        $shift->setTime($hour, 0, 0);
        $twenty_for_hours_from_now = $now->addHours(24);
        \Log::info($shift->toDateTimeString());
        \Log::info($twenty_for_hours_from_now->toDateTimeString());
        return $shift->lessThan($twenty_for_hours_from_now);
    }

    /**
     * Remove the time slots for today for each courier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateScheduleForNextDay()
    {
        // get all couriers
        $couriers = Role::where('name', 'courier')->first()->users;
        foreach ($couriers as $courier) {
            $avail_time = json_decode($courier->available_times);
            // pop of all of today's time slots for each courier
            $avail_time[Carbon::now($this->tz())->dayOfWeek] = [''];
            $courier->available_times = json_encode($avail_time);
            $courier->save();
        }
        return back();
    }

    private function pr($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}
