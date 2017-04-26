<?php

namespace App\Http\Controllers\Admin\OrderRelated;

use App\CustomClasses\Courier\CourierPayment;
use App\CustomClasses\Stats\TimeFrames;
use App\Http\Controllers\Controller;
use App\Models\MoneyRelated\WorkerExtraEarnings;
use App\User;
use Illuminate\Http\Request;

class CourierPaymentController extends Controller
{
    public function showCourierPaymentSummary(Request $request)
    {
        $couriers = User::ofType('courier');
        if ((empty($time_frame = $request->query('TimeFrame')))) {
            $time_frame = TimeFrames::$TWO_WEEKS_AGO;
        }
        $courier_payment = new CourierPayment($couriers, $time_frame);
        $time_frames = TimeFrames::getNamedTimeFrames();
        return view('admin.couriers.payment.courier_payment_summary',
            compact('courier_payment', 'time_frames', 'time_frame'));
    }

    public function showCourierWorkSummary($courier_id, User $user)
    {
        $courier = $user->find($courier_id);
        $time_frames = TimeFrames::getNamedTimeFrames();
        return view('admin.couriers.payment.courier_work_summary', compact('courier', 'time_frames'));
    }

    public function showUpdateWorkDetails(WorkerExtraEarnings $wee, $worker_earnings_id)
    {
        $worker_earnings = $wee->find($worker_earnings_id);
        return view('admin.couriers.payment.update_work_details', compact('worker_earnings'));
    }

    public function showAddHoursWorked(User $user, $worker_id)
    {
        $worker = $user->find($worker_id);
        return view('admin.couriers.payment.add_hours', compact('worker'));
    }

    public function updateOrCreateHoursWorked(Request $request)
    {
        $hours_worked = $request->input('hours_worked');
        WorkerExtraEarnings::updateOrCreate(array_except($request->all(), ['_token']));
        return back()->with('status_good',
            $hours_worked . ($hours_worked > 1 ? ' hours' : ' hour') . ' saved for ' . User::find($request->input('worker_id'))->name);
    }

    public function removeHoursWorked()
    {

    }
}
