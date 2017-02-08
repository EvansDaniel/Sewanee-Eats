<?php

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Database\Seeder;

class MenuItemsOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $q = [1, 2, 3, 4, 5, 6, 7, 8];
        $orders = Order::all();
        foreach ($orders as $order) {
            $menu_item = MenuItem::orderByRaw("RAND()")->first();
            DB::table('menu_items_orders')->insert([
                'menu_item_id' => $menu_item->id,
                'order_id' => $order->id,
                'special_instructions' => 'My Special Instructions',
                'quantity' => $q[mt_rand(0, count($q) - 1)]
            ]);
            $menu_item = MenuItem::orderByRaw("RAND()")->first();
            DB::table('menu_items_orders')->insert([
                'menu_item_id' => $menu_item->id,
                'order_id' => $order->id,
                'special_instructions' => 'My Special Instructions',
                'quantity' => $q[mt_rand(0, count($q) - 1)]
            ]);
            $menu_item = MenuItem::orderByRaw("RAND()")->first();
            DB::table('menu_items_orders')->insert([
                'menu_item_id' => $menu_item->id,
                'order_id' => $order->id,
                'special_instructions' => 'My Special Instructions',
                'quantity' => $q[mt_rand(0, count($q) - 1)]
            ]);
        }
    }
}
