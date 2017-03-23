<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
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

    use RegistersUsers;

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
        $user->available_times = json_encode([null]);
        $user->save();
        $user->roles()->attach($data['role_type']);
        return $user;
    }
}
