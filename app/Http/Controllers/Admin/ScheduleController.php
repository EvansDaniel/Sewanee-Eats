<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\Schedule\Shift;
use App\CustomTraits\HandlesTimeRanges;
use App\Http\Controllers\Controller;
use App\Models\TimeRange;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * TODO: the find function along with all other DB accesses here need to be bullet proofed
 * Class ScheduleController
 * @package App\Http\Controllers\Admin
 */
class ScheduleController extends Controller
{

    use HandlesTimeRanges;

    public function __construct()
    {

    }

    public function showSchedule()
    {
        $shift = new Shift();
// to show the start and end of this weeks schedule
        $start_of_week = Carbon::now()->dayOfWeek == Carbon::MONDAY ? Carbon::now() : new Carbon('last Monday');
        $end_of_week = Carbon::now()->dayOfWeek == Carbon::SUNDAY ? Carbon::now() : new Carbon('next Sunday');
        $courier_types = [CourierTypes::WALKER, CourierTypes::BIKER, CourierTypes::DRIVER];
        $courier_type_names = ['Walker', 'Biker', 'Driver'];
        $shift_type = TimeRangeType::SHIFT;
        $days_of_week = $this->getDayOfWeekNames();
        return view('admin.schedule.schedulev2',
            compact('shift', 'start_of_week', 'end_of_week',
                'courier_types', 'courier_type_names', 'shift_type', 'days_of_week'));
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
        $shifts = $shift->getCurrentShifts();
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.schedule.create_shift',
            compact('shift', 'shifts', 'day_of_week_names'));
    }

// TODO: refactor to use cool ajax calls so page doesn't have to reload
    public function assignWorkerToShift(Request $request)
    {
        $time_range = TimeRange::find($request->input('shift_id'));
        $shift = new Shift($time_range);
        $worker_id = $request->input('worker_id');
        $courier_type = $request->input('courier_type');
        $ret_val = $shift->assignWorker($worker_id, $courier_type);
        if ($ret_val == -1) {
            return back()->with('status_bad', 'This shift already has manager');
        }
// no error
        return back()->with('status_good', 'Worker added to shift successfully');
    }

    public function showUpdateShift(int $shift_id)
    {
        $time_range = TimeRange::find($shift_id);
        $shift = new Shift($time_range);
// show current shifts so that user has reference
        $shifts = $shift->getCurrentShifts();
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.schedule.update_shift',
            compact('shift', 'shifts', 'day_of_week_names'));
    }

    public function createShift(Request $request)
    {
        $shift = new TimeRange;
// this controller handles shifts
        $shift = $this->timeRangeSetup($shift, $request, TimeRangeType::SHIFT);
        $shift_check = new Shift($shift);
        if (empty(($valid_shift_message = $shift_check->isValidShift()))) {
            $shift->save();
            return back()->with('status_good', 'New shift created!');
        } else {
            return back()->with('status_bad',
                $valid_shift_message)->withInput();
        }
    }

    public function deleteShift(Request $request)
    {
        $shift_id = $request->input('shift_id');
        $time_range = TimeRange::find($shift_id);
// this will affect have the affect of deleting any rows workers
// attached to the shift in the time_ranges_users table
// as well as deleting this time range
        $time_range->delete();
        return back()->with('status_good', 'Shift deleted');
    }

    public function removeWorkerFromShift(Request $request)
    {
        $time_range = TimeRange::find($request->input('shift_id'));
        $shift = new Shift($time_range);
        $shift->removeWorkerFromShift($request->input('worker_id'));
        return back()->with('status_good', 'Worker removed from shift');
    }

    public function updateShift(Request $request)
    {
        $shift_id = $request->input('shift_id');
        $time_range = TimeRange::find($shift_id);
        $this->timeRangeSetup($time_range, $request, TimeRangeType::SHIFT);
        $shift = new Shift($time_range);
        if (empty(($valid_shift_message = $shift->isValidShift()))) {
            $time_range->save();
            return redirect()->route('showSchedule')->with('status_good', 'Shift updated!');
        } else {
            return back()->with('status_good', $valid_shift_message);
        }
    }
}
