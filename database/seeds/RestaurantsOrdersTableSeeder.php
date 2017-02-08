<?php

use App\Models\Order;
use Illuminate\Database\Seeder;

class RestaurantsOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO: This needs to be tested to make sure
        // it is doing what is expected
        $orders = Order::all();
        foreach ($orders as $order) {
            $rest_ids = [];
            foreach ($order->menuItems as $item) {
                if (in_array($item->restaurant->id, $rest_ids)) {
                    continue;
                }
                $rest_ids[] = $item->restaurant->id;
            }
            $order->restaurants()->attach($rest_ids);
        }
    }
}
