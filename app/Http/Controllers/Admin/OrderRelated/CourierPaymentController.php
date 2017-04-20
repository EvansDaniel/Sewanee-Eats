<?php

namespace App\Http\Controllers\Admin\OrderRelated;

use App\CustomClasses\Courier\CourierPayment;
use App\CustomClasses\Stats\TimeFrames;
use App\Http\Controllers\Controller;
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
        return view('admin.couriers.courier_payment_summary',
            compact('courier_payment', 'time_frames', 'time_frame'));
    }

    public function showCourierOrderSummary($courier_id, User $user)
    {
        $courier = $user->find($courier_id);
        $time_frames = TimeFrames::getNamedTimeFrames();
        return view('admin.couriers.courier_orders_summary', compact('courier', 'time_frames'));
    }
}
