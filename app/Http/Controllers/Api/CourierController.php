<?php

namespace App\Http\Controllers\Api;

use App\CustomTraits\IsAvailable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\User;
use Auth;
use Carbon\Carbon;

class CourierController extends Controller
{
    use IsAvailable;

    // TODO: abstract this to IsAvailable with method name "filterAvailableObjects($objects,$day,$time)"
    public function getOnlineCouriersForDayTime($day, $time)
    {
        $courier_role = Role::where('name', 'employee')->first();
        $all_couriers = $courier_role->users;
        $available_couriers = [];
        $timezone = 'America/Chicago';
        $time = Carbon::createFromTime($time, 0, 0, $timezone);
        foreach ($all_couriers as $courier) {
            if ($courier->isAvailable($day, $time)) {
                $available_couriers[] = $courier;
            }
        }
        return $available_couriers;
    }

    public function userIsAvailable($day, $time)
    {
        return $this->isAvailableNow(User::find(Auth::id()));
    }
}
