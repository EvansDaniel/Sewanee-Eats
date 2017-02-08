<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;

class DashboardController extends Controller
{

    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    public function showDashboard()
    {
        $open_orders = Order::where('is_open', 1)->orderBy('created_at', 'DESC')
            ->paginate(8);
        $closed_orders = Order::where('is_open', 0)->orderBy('created_at', 'DESC')
            ->paginate(8);
        $admin_role = Role::where('name', 'admin')->first();
        $admins = $admin_role->users;
        $courier_role = Role::where('name', 'courier')->first();
        $couriers = $courier_role->users;
        return view('admin.dashboard', compact('closed_orders', 'open_orders',
            'admins', 'couriers'));
    }

    public function orderSummary($id)
    {
        $order = Order::find($id);
        return view('admin.order_summary', compact('order'));
    }

    // Some tasks:
    // Promote user to admin
    // Delete users
    // Adding new restaurants/menus
    // Deleting/updating existing menus
}
