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
        Role::create([
            'name' => 'admin',
            'description' => 'Can create restaurant menus and manage users'
        ]);

        Role::create([
            'name' => 'default',
            'description' => 'Can order food and use non-privileged functionality'
        ]);
    }
}
