<?php

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for weekly specials is_available_to_customers will default false,
        // on demand defaults to true
        factory(Restaurant::class, 5)->create([
            'seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL,
            'is_available_to_customers' => false
        ]);
        //$weekly_specials = Restaurant::weeklySpecials()->get();
        /*// attach a time range to the weekly special, ONLY ONE IS ALLOWED
        // B/C IT REPRESENTS THE PAYMENT TIME FRAME
        foreach ($weekly_specials as $r) {
            $payment_time_frame = $this->makeTimeRange(TimeRangeType::WEEKLY_SPECIAL);
            $payment_time_frame->restaurant_id = $r->id;
            $payment_time_frame->save();
        }*/
        factory(Restaurant::class, 5)->create([
            'seller_type' => RestaurantOrderCategory::ON_DEMAND,
            'is_available_to_customers' => true
        ]);
    }

    private function makeTimeRange($time_range_type)
    {
        $time_range = new TimeRange;
        $time_range->start_dow = 'Monday';
        $time_range->end_dow = 'Wednesday';
        $time_range->start_hour = 0;
        $time_range->start_min = 0;
        $time_range->end_hour = 17;
        $time_range->end_min = 55;
        $time_range->time_range_type = $time_range_type;
        return $time_range;
    }
}
