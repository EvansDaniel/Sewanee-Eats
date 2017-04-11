<?php

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\Schedule\Shift;
use App\Http\Controllers\Admin\TimeRangeController;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\TimeRange;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\CustomTraits\HandlesTimeRanges;

class TimeRangesSeeder extends Seeder
{
    use HandlesTimeRanges;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courier_test = User::where('email','seatstest17@gmail.com')->first();
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->format('l'),
            'end_dow' => Carbon::now()->addDay()->format('l'),
            'start_hour' => 0,
            'end_hour' => 23,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
        if ($courier_test->hasRole('courier')) {
            $shift = new Shift($time_range);
            $shift->assignWorker($courier_test->id, CourierTypes::DRIVER);
        }
        $restaurants = Restaurant::onDemand()->get();
        if (!empty($restaurants)) {
            foreach ($restaurants as $restaurant) {
                $this->copyRestTimeRangesToMenuItems($restaurant);
            }
        }
    }
}
