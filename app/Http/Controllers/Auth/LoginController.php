<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return string the route to redirect to
     */
    protected function redirectTo()
    {
        if (\Auth::user()->hasRole('courier')) {
            return route('courierShowSchedule');
        } else if (\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('manager')) {
            return route('showAdminDashboard');
        } else { // normal user
            return route('home');
        }
    }
}
