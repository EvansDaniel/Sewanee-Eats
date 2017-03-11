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
            $order->delivery_location = $locations[mt_rand(0, 3)];
            //$order->contact_number_of_user = 9316919435;
            $order->was_refunded = mt_rand(0, 1);
            $order->c_name = "My Name";
            $order->paid_with_venmo = 1;
            $order->is_weekly_special = 1;
            $order->is_delivered = 1;
            if ($order->is_delivered)
                $order->is_open_order = 0;
            else
                $order->is_open_order = mt_rand(0, 1);
            $order->paid_with_venmo = mt_rand(0, 1);
            $order->email_of_customer = "evansdb0@sewanee.edu";
            $order->is_cancelled = false;
            $order->save();
            // this will be created at order creation time
            $priceInfo = new OrderPriceInfo;
            $priceInfo->order_id = $order->id;
            $priceInfo->subtotal = 12;
            $total_price = mt_rand(15, 50);
            $priceInfo->total_price = $total_price;
            $priceInfo->profit = .25 * $total_price;
            $priceInfo->stripe_fees = .25 * $total_price;
            $priceInfo->delivery_fee = .25 * $total_price;
            $priceInfo->cost_of_food = .25 * $total_price;
            $priceInfo->state_tax_charged = .0925;
            $priceInfo->save();
        }
    }
}
