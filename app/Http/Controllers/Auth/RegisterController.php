<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CourierInfo;
use App\Models\Role;
use App\User;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RedirectsUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $roles = Role::all();
        $role_user = Role::ofType('user')->first();
        return view('auth.register', compact('roles', 'role_user'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // don't log the user in if they are already logged in,
        // tthe implication is that this is an admin signed in
        if (!Auth::check()) {
            $this->guard()->login($user);
        }
        if (Auth::check()) { // if admin is logged in, redirect back after new user creation
            return redirect()->route('showAdminDashboard')->with('status_good', 'The new user has been created!');
        }
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // create these roles if they don't exist
        if (count(Role::where('name', 'admin')->first()) == 0) {
            // id = 1
            \Eloquent::unguard();
            Role::create([
                'name' => 'admin',
                'description' => 'Can create all website data, manage users, and provide any business services'
            ]);
            // id = 2
            Role::create([
                'name' => 'user',
                'description' => 'Can order food and use non-privileged functionality'
            ]);
            // id = 3
            Role::create([
                'name' => 'courier',
                'description' => 'Can deliver food and view order requests, receives a paycheck'
            ]);
            \Eloquent::reguard();
        }

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();
        $user->roles()->attach($data['role_type']);
        if ($this->requestHasTypeCourier($data['role_type'])) {
            // save the courier's extra info
            $courier_info = new CourierInfo;
            $courier_info->phone_number = $data['phone_number'];
            $courier_info->is_delivering_order = false;
            $courier_info->current_order_id = null;
            $courier_info->user_id = $user->id;
            $courier_info->save();
        }
        return $user;
    }

    private function requestHasTypeCourier($dataRoleType)
    {
        $role_courier = Role::ofType('courier')->first()->id;
        foreach ($dataRoleType as $type) {
            if ($role_courier == $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
