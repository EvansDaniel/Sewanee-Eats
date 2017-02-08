<?php

use App\Models\Order;
use App\User;
use Illuminate\Database\Seeder;

class CouriersOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();
        $users = User::all();
        $couriers = [];
        foreach ($users as $user) {
            if ($user->hasRole('courier')) {
                $couriers[] = $user;
            }
        }
        $size = count($couriers);
        foreach ($orders as $order) {
            $order->couriers()->attach($couriers[mt_rand(0, $size - 1)]->id);
        }
    }
}
