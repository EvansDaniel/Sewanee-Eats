<?php

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
        // ATTENTION: Fake couriers are created in RolesUsersTableSeeder

        User::create([
            'name' => 'Daniel Evans',
            'email' => 'evansdb0@sewanee.edu',
            'password' => bcrypt('dsmith'),
        ]);

        User::create([
            'name' => 'Blaise Iradukunda',
            'email' => 'iradub0@sewanee.edu',
            'password' => bcrypt('blaise'),
        ]);

        User::create([
            'name' => 'Tari Kandemiri',
            'email' => 'kandeta0@sewanee.edu',
            'password' => bcrypt('tariro'),
        ]);

    }
}
