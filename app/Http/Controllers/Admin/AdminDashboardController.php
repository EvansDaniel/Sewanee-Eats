<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\ScheduleFiller;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use App\User;
use Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    public function showDashboard()
    {
        // by DESC to show most recent first
        $open_venmo_orders = Order::where(['is_open_order' => 1, 'paid_with_venmo' => 1])->orderBy('created_at', 'DESC')
            ->paginate(8);
        if (count($open_venmo_orders)) {
            //$open
        }
        $open_n_venmo_orders = Order::where(['is_open_order' => 1, 'paid_with_venmo' => 0])->orderBy('created_at', 'DESC')->paginate(8);
        if (count($open_n_venmo_orders)) {

        }
        $closed_orders = Order::where('is_open_order', 0)->orderBy('created_at', 'DESC')
            ->paginate(8);
        $admin_role = Role::where('name', 'admin')->first();
        $admins = $admin_role->users;
        $courier_role = Role::where('name', 'courier')->first();
        $couriers = $courier_role->users;
        return view('admin.dashboard', compact('closed_orders', 'open_n_venmo_orders', 'open_venmo_orders',
            'admins', 'couriers'));
    }

    public function orderSummary($id)
    {
        $order = Order::find($id);
        return view('admin.order_summary', compact('order'));
    }

    public function showSchedule()
    {
        // check current time, if it is < 02:00, then $today = day - 1
        $schedule_filler = new ScheduleFiller();
        $courier = User::find(Auth::id());
        return view('courier.schedule', compact('schedule_filler', 'courier'));
    }

    // Some tasks:
    // Promote user to admin
    // Delete users
    // Adding new restaurants/menus
    // Deleting/updating existing menus
}
