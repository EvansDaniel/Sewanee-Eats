<?php

use App\Models\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Daniel Evans',
            'email' => 'evansdb0@sewanee.edu',
            'password' => bcrypt('dsmith'),
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        User::create([
            'name' => 'Blaise Iradukunda',
            'email' => 'iradub0@sewanee.edu',
            'password' => bcrypt('blaise'),
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        User::create([
            'name' => 'Tari Kandemiri',
            'email' => 'kandeta0@sewanee.edu ',
            'password' => bcrypt('tariro'),
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        // Create a factory to create more users
    }
}
