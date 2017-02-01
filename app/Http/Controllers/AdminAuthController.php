<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('adminAuth');
    }

    public function authenticate(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password, 'admin' => 1])) {
            // Authentication passed...
            return redirect()->intended('/');
        }
    }
}
