<?php

use App\Models\Role;
use App\User;
use Illuminate\Database\Seeder;

class RolesUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courier_role = Role::where('name', 'courier')->first();
        $admin_role = Role::where('name', 'admin')->first();

        $danny = User::where('email', 'evansdb0@sewanee.edu')->first();
        $blaise = User::where('email', 'iradub0@sewanee.edu')->first();
        $tari = User::where('email', 'kandeta0@sewanee.edu')->first();

        $danny->roles()->attach([$admin_role->id, $courier_role->id]);
        $blaise->roles()->attach([$admin_role->id, $courier_role->id]);
        $tari->roles()->attach([$admin_role->id, $courier_role->id]);

        $num_couriers = 10;

        // format of available_times
        /*[
            'monday','tuesday','....','sunday'
        ]*/
        $available_times = [
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
        ];
        $faker = Faker\Factory::create();
        for ($i = 0; $i < $num_couriers; $i++) {
            $courier = new User;
            $courier->name = $faker->name;
            $courier->email = $faker->companyEmail;
            $courier->password = "mypass";
            $courier->remember_token = str_random(10);
            $courier->available_times = json_encode($available_times);
            $courier->save();
            $courier->roles()->attach($courier_role->id);
            shuffle($available_times);
        }
    }
}
