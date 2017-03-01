<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\ScheduleFiller;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class CourierDashboardController extends Controller
{
    // currently only shows the schedule
    // TODO: show a courier's payment
    public function showDashboard()
    {
        return view('courier.dashboard');
    }

    public function showSchedule()
    {
        // check current time, if it is < 02:00, then $today = day - 1
        $schedule_filler = new ScheduleFiller();
        $courier = User::find(Auth::id());
        return view('courier.schedule', compact('schedule_filler', 'courier'));
    }
}
