<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function showDashboard()
    {
        return view('admin.dashboard');
    }

    // Some tasks:
    // Promote user to admin
    // Delete users
    // Adding new restaurants/menus
    // Deleting/updating existing menus
}
