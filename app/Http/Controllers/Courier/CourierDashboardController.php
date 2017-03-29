<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\Schedule\Shift;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CourierDashboardController extends Controller
{
    // currently only shows the schedule
    // TODO: show a employee's payment
    public function showDashboard()
    {
        return view('employee.dashboard');
    }

    public function showSchedule()
    {
        $shift = new Shift();
        // to show the start and end of this weeks schedule
        $start_of_week = Carbon::now()->dayOfWeek == Carbon::MONDAY ? Carbon::now() : new Carbon('last Monday');
        $end_of_week = Carbon::now()->dayOfWeek == Carbon::SUNDAY ? Carbon::now() : new Carbon('next Sunday');
        $courier_types = [CourierTypes::BIKER, CourierTypes::DRIVER];
        $courier_type_names = ['Biker', 'Driver'];
        return view('employee.schedule',
            compact('shift', 'start_of_week', 'end_of_week', 'courier_types', 'courier_type_names'));
    }

    public function showPaymentBreakdown()
    {
        // this is the method that will show the courier his/her payment for the pay period, per order, etc.
        // this will mostly be read only data
    }
}
