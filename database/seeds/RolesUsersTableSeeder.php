<?php

use App\Models\CourierInfo;
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
        $manager_role = Role::where('name', 'manager')->first();

        $danny = User::where('email', 'evansdb0@sewanee.edu')->first();
        $blaise = User::where('email', 'iradub0@sewanee.edu')->first();
        $tari = User::where('email', 'kandeta0@sewanee.edu')->first();

        $danny->roles()->attach([$admin_role->id, $manager_role->id]);
        $blaise->roles()->attach([$admin_role->id, $manager_role->id]);
        $tari->roles()->attach([$admin_role->id, $manager_role->id]);

        $num_couriers = 10;

        $faker = Faker\Factory::create();
        for ($i = 0; $i < $num_couriers; $i++) {
            $courier = new User;
            $courier->name = $faker->name;
            $courier->email = $faker->companyEmail;
            $courier->password = bcrypt("mypass");
            $courier->remember_token = str_random(10);
            $courier->save();
            $courier->roles()->attach($courier_role->id);
            $courier_info = new CourierInfo;
            $courier_info->phone_number = "9316913594";
            $courier_info->is_delivering_order = false;
            $courier_info->user_id = $courier->id;
            $courier_info->save();
        }
        $courier = new User;
        $courier->name = 'Test Courier';
        $courier->email = 't@t.com';
        $courier->password = bcrypt('dsmith');
        $courier->remember_token = str_random(10);
        $courier->save();
        $courier->roles()->attach($courier_role->id);
        $courier_info = new CourierInfo;
        $courier_info->phone_number = "9316913594";
        $courier_info->is_delivering_order = false;
        $courier_info->current_order_id = 0;
        $courier_info->user_id = $courier->id;
        $courier_info->save();
    }
}
