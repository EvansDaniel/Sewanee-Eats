<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Stats\OrderStats;
use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    public function showDashboard()
    {
        $stats = new OrderStats();
        return view('admin.main.dashboard2', compact(/*'closed_orders', 'open_n_venmo_orders', 'open_venmo_orders',*/
            'admins', 'couriers', 'stats'));
    }

    public function orderSummary($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.order_summary', compact('order'));
    }

    public function showSchedule()
    {
        // check current time, if it is < 02:00, then $today = day - 1
        /*$schedule_filler = new ScheduleFiller();
        $courier = User::find(Auth::id());*/
        return view('admin.main.schedulev2'/*, compact('schedule_filler', 'courier')*/);
    }

    // Some tasks:
    // Promote user to admin
    // Delete users
    // Adding new restaurants/menus
    // Deleting/updating existing menus
}
