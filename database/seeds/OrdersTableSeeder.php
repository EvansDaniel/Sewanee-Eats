<?php

use App\Models\Order;
use App\Models\OrderPriceInfo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 10)->create();
        $orders = Order::all();
        foreach ($orders as $order) {
            $carbon = new Carbon($order->created_at);
            $carbon->hour(mt_rand(0, Carbon::now()->hour));
            $order->created_at = $carbon;
            $order->save();
            $pi = new OrderPriceInfo;
            $pi->order_id = $order->id;
            $pi->total_price = 20;
            $pi->subtotal = 17;
            $pi->profit = 3;
            $pi->cost_of_food = 13;
            $pi->stripe_fees = 1;
            $pi->delivery_fee = 3;
            $pi->tax_charged = 2;
            $pi->tax_percentage = 1.0925;
            $pi->save();
        }
    }
}
