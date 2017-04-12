<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/9/17
 * Time: 5:52 AM
 */

namespace App\TestTraits;

use App\Contracts\ResourceTimeRange;
use App\CustomClasses\Availability\TimeRangeType;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\TimeRange;
use App\User;
use Carbon\Carbon;

trait AvailabilityMaker
{
    public function makeShiftAndRestAvailableNow($restaurant_type)
    {
        \Eloquent::unguard();
        // make it so that shift now is true
        $rest = factory(Restaurant::class)->create([
            'seller_type' => $restaurant_type,
            'is_available_to_customers' => true
        ]);
        $this->userAndShiftNow();
        $this->makeResourceAvailable($rest, 'restaurant_id');
        \Eloquent::reguard();
        return $rest;
    }

    public function userAndShiftNow()
    {
        \Eloquent::unguard();
        // make a time_range that is available now
        $courier = factory(User::class)->create();
        $shift = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
        Role::create([
            'name' => 'courier'
        ]);
        $courier->roles()->attach(Role::ofType('courier')->first()->id);
        $shift->users()->attach($courier->id);
        \Eloquent::reguard();
        return $courier;
    }

    public function makeResourceAvailable(ResourceTimeRange $obj, $id_name)
    {
        return factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            $id_name => $obj->getId(),
            'time_range_type' => $obj->getTimeRangeType()
        ]);
    }

    public function makeShiftNext()
    {
        // make a time_range that is available now
        return $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->addHours(6)->format('l'),
            'end_dow' => Carbon::now()->addHour(12)->format('l'),
            'start_hour' => Carbon::now()->addHours(6)->hour,
            'end_hour' => Carbon::now()->addHours(6)->hour,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
    }

    public function makeShiftForNow()
    {
        // make a time_range that is available now
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
    }
}