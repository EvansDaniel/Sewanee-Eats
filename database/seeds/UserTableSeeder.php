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
        // All available times for couriers will be in the range
        // of two hours i.e. '10:00-12:00'. This is b/c this is the current
        // length of a shift
        User::create([
            'name' => 'Daniel Evans',
            'email' => 'evansdb0@sewanee.edu',
            'password' => bcrypt('devans@yeah'),
        ]);

        User::create([
            'name' => 'Blaise Iradukunda',
            'email' => 'iradub0@sewanee.edu',
            'password' => bcrypt('blezzoh@1995'),
        ]);

        User::create([
            'name' => 'Tari Kandemiri',
            'email' => 'kandeta0@sewanee.edu',
            'password' => bcrypt('tari1995'),
        ]);
    }
}
