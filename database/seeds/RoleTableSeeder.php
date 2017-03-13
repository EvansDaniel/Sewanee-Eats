<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id = 1
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
            'name' => 'employee',
            'description' => 'Can deliver food and view order requests, receives a paycheck'
        ]);
    }
}
