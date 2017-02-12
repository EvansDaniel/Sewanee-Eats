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

        $available_times = json_encode([
            [
                '10:00-12:00',
                '20:00-22:00',
                ''
            ],
            [
                '08:00-10:00',
                '22:00-00:00',
                ''
            ],
            [
                '16:00-18:00',
                '',
                ''
            ],
            [
                '18:00-20:00',
                '16:00-18:00',
                ''
            ],
            [
                '12:00-00:00',
                '00:00-02:00',
                ''
            ],
            [
                '18:00-20:00',
                '20:00-22:00',
                '22:00-24:00'
            ],
            [
                '14:00-16:00',
                '16:00-18:00',
                '18:00-20:00'
            ]
        ]);
        User::create([
            'name' => 'Daniel Evans',
            'email' => 'evansdb0@sewanee.edu',
            'password' => bcrypt('dsmith'),
            'available_times' => $available_times
        ]);

        User::create([
            'name' => 'Blaise Iradukunda',
            'email' => 'iradub0@sewanee.edu',
            'password' => bcrypt('blaise'),
            'available_times' => $available_times
        ]);

        User::create([
            'name' => 'Tari Kandemiri',
            'email' => 'kandeta0@sewanee.edu',
            'password' => bcrypt('tariro'),
            'available_times' => $available_times
        ]);
    }
}
