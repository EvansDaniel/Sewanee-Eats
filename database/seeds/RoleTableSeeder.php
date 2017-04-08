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
        Role::create([
            'name' => 'manager',
            'description' => 'Acts on behalf of the company during on demand order shifts'
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

        Role::create([
            'name' => 'tester',
            'description' => 'User for testing in staging'
        ]);
    }
}
