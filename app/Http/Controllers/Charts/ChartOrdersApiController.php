<?php

namespace App\Http\Controllers\Charts;

use App\CustomTraits\IsAvailable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class ChartOrdersApiController extends Controller
{
    use IsAvailable;

    public function projectedWeeklySpecialOrders()
    {
        $monday = Carbon::now($this->tz())->startOfWeek();
        $nextWeek = $monday->addDays(7);
        $weekly_specials = Order::where([
            ['created_at','>=',$monday],
            ['is_open_order',0]
        ])->get();
        return $weekly_specials;
    }

    public function actualWeeklySpecialOrders()
    {
        $monday = Carbon::now($this->tz())->startOfWeek();
        $nextWeek = $monday->addDays(7);
        $confirmed_weekly_specials = Order::where([
                ['created_at','>=',$monday]/*,
                ['is_open_order',0],
                ['is_delivered',1]*/
        ])->get();
        /*$day_buckets = [];
        foreach($confirmed_weekly_specials as $weekly_special) {
            $day_buckets[$weekly_special->created_at]++;
        }*/
        return $confirmed_weekly_specials;
    }
}
