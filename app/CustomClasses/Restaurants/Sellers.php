<?php

namespace App\CustomClasses\Restaurants;

use App\Models\Restaurant;
use App\Models\SpecialEvent;

class Sellers
{
    protected $on_demand_rests;
    protected $weekly_specials;
    protected $events;

    public function __construct()
    {
        $this->events = SpecialEvent::all();
        $this->weekly_specials = Restaurant::weeklySpecials()->get();
        $this->on_demand_rests = Restaurant::onDemand()->get();
    }

    /**
     * @return mixed
     */
    public function getOnDemandRests()
    {
        return $this->on_demand_rests;
    }

    /**
     * @return mixed
     */
    public function getWeeklySpecials()
    {
        return $this->weekly_specials;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}