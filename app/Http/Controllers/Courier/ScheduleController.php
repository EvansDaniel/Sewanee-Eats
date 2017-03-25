<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Schedule\Shift;
use App\Http\Controllers\Controller;
use App\Models\TimeRange;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function __construct()
    {

    }

    public function showSchedule()
    {
        //depending on the logged in user's roles (courier vs admin)
        // we show a read only or read and write schedule
        return view('admin.schedule.schedulev2');
    }

    public function showShifts()
    {
        $shift = new Shift();
        $shifts = $shift->getCurrentShifts();
        return view('admin.schedule.shifts', compact('shifts'));
    }

    public function showCreateShift()
    {
        $shift = new Shift();
        return view('admin.schedule.create_shift',
            compact('shift'));
    }

    public function showUpdateShift(Request $request)
    {
        $time_range = TimeRange::find($request->input('time_range_id'));
        $shift = new Shift($time_range);
        return view('admin.schedule.update_shift', compact('shift'));
    }

    public function createShift(Request $request)
    {
        $shift = new TimeRange;
        $shift = $this->shiftSetUp($shift, $request);
        $shift_check = new Shift($shift);
        if ($shift_check->validShiftCreation()) {
            $shift->save();
            return back()->with('status_good', 'New shift created!');
        } else {
            return back()->with('status_bad',
                'The start day of the week must be before the end day of week and 
                the end day of week must be the same as the start day of week 
                or the next day after it');
        }
    }

    private function shiftSetUp(TimeRange $shift, Request $request)
    {
        $shift->start_dow = $request->input('start_dow');
        $shift->start_hour = $request->input('start_hour');
        $shift->start_min = $request->input('start_min');
        $shift->end_dow = $request->input('end_dow');
        $shift->end_hour = $request->input('end_hour');
        $shift->end_min = $request->input('end_min');
        return $shift;
    }

    public function updateShift(Request $request)
    {

    }

    public function deleteShift()
    {

    }
}
