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

        $e = [
            'a@gmail.com',
            'b@gmail.com',
            'c@gmail.com',
            'd@gmail.com',
            'e@gmail.com',
        ];
        for ($i = 0; $i < 5; $i++) {
            $courier = new User;
            $courier->name = "Courier Name";
            $courier->email = $e[$i];
            $courier->password = "mypass";
            $courier->remember_token = str_random(10);
            $courier->save();
            $courier->roles()->attach($courier_role->id);
        }

    }
}
