<?php

namespace App\Http\Controllers\Courier;

use App\CustomTraits\IsAvailable;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use IsAvailable;

    public function showSchedule()
    {
        return view('courier.schedule');
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
            Carbon::createFromTime($hour, 0, 0, $this->getTimezone()))
        ) {
            return back()->with('status_bad', 'You have already added yourself to that time slot');
        }
        //\Log::info($times[$day]); // debugging
        $times[$day][] = $time_to_add;
        $user->available_times = json_encode($times);
        $user->save();
        return back()->with('status_good', 'Your schedule has been updated');
    }

    private function convertTimeToDBTime($hour)
    {
        if ($hour < 9) {
            return "0" . ($hour - 1) . ":00-0" . ($hour + 1) . ":00";
        } else if ($hour == 9) {
            return "0" . ($hour - 1) . ":00-" . ($hour + 1) . ":00";
        } else if ($hour == 23) {
            return ($hour - 1) . ":00-" . "00:00";
        } else {
            return ($hour - 1) . ":00-" . ($hour + 1) . ":00";
        }
    }

    // time is an integer that is directly in between
    // the time slot is designates i.e. 7 -> 06:00-08:00

    public function removeCourierFromTimeSlot(Request $request)
    {
        // TODO: courier cannot remove him/herself if his/her shift is within
        // TODO: 24 hours
        $user = User::find(Auth::id());
        $day = $request->input('day');
        $hour = $request->input('hour');

        // check if courier is available for this time slot
        if (!$this->isAvailableOnDayAtTime($user, $day,
            Carbon::createFromTime($hour, 0, 0, $this->getTimezone()))
        ) {
            return back()->with('status_bad',
                'You are currently not scheduled for this time slot');
        }

        // check if shift is within ~24 hours from now. Can't remove if so
        if (Carbon::now()->dayOfWeek == $day || Carbon::now()->dayOfWeek == $day - 1) {
            return back()->with('status_bad',
                'Your shift is within 24 hours, and you cannot be removed. 
                 Contact one of the admins if you really cannot work your shift');
        }

        $time = $this->convertTimeToDBTime($hour);
        \Log::info("hour " . $hour);
        \Log::info("time " . $time);
        $times = json_decode($user->available_times, true);
        // find $time index
        $i = 0;
        \Log::info($times[$day]);
        while ($time != $times[$day][$i]) {
            \Log::info($i);
            $i++;
        }
        unset($times[$day][$i]);
        $user->available_times = json_encode($times);
        $user->save();
        return back()->with('status_good', 'You have been removed from that time slot');
    }
}
