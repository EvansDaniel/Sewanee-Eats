<?php

use App\CustomTraits\PriceInformation;
use App\Models\Order;
use App\Models\OrderPriceInfo;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    use PriceInformation;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Order::class,10)->create(); This isn't working!!! why not?
        $locations = $location = ['Smith Hall', 'Tuckaway', 'Library', 'Quintard'];
        for ($i = 0; $i < 30; $i++) {
            $order = new Order;
            $order->is_open = mt_rand(0, 1);
            $order->location_of_user = $locations[mt_rand(0, 3)];
            $order->contact_number_of_user = 9316919435;
            $order->was_refunded = mt_rand(0, 1);
            $order->save();
            // this will be created at order creation time
            $priceInfo = new OrderPriceInfo;
            $priceInfo->order_id = $order->id;
            $total_price = mt_rand(15, 50);
            $priceInfo->total_price = $total_price;
            $priceInfo->profit = .25 * $total_price;
            $priceInfo->state_tax = .0925;
            $priceInfo->save();
        }
    }
}
