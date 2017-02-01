<?php

namespace App\CustomTraits;

use App\Models\Restaurant;
use Carbon\Carbon;

trait RestaurantInformation {

    /**
     * @param $restaurant Restaurant fully qualified instance of Restaurant model
     * @return bool returns true if that restaurant is open for business
     */
    private function restaurantIsOpen(Restaurant $restaurant) {
        $timezone = 'America/Kentucky/Louisville';
        $day = Carbon::now()->dayOfWeek-1;
        $current_hour = Carbon::now($timezone)->hour-1;
        $current_minute =  Carbon::now($timezone)->minute;

        // array of days of the week with time ranges for each day
        // that the business is open
        $open_hours = json_decode($restaurant->hours_open,true);
        $isOpen = false;
        // loop through all the time ranges in which $restaurant is open
        foreach ($open_hours[$day] as $unparsed_time_range) {

            if($isOpen) break; // found a time range that is open

            // get the time today as central time zone
            // parse time range that ultimately came from the DB
            $time_range = explode("-", $unparsed_time_range);
            $open = $time_range[0];
            $close = $time_range[1];

            if ($close < $open) {
                // if time flipped from pm to am and restaurant hasn't closed yet
                if ($current_hour <= $open && $current_hour < $close) {
                    // if $restaurant will close in < 1 hour, then
                    // delivery person needs at least 30 minutes of breathing room to delivery
                    // could switch above to $current_hour+1 < $close to give an hour of breathing room
                    if($current_hour + 1 == $close) {
                        $isOpen = $current_minute <= 30;
                    }
                    else {
                        $isOpen = true;
                    }
                }
                $isOpen = $current_hour >= $open;
            } else {
                if ($current_hour >= $open && $current_hour < $close) {
                    // if $restaurant will close in < 1 hour, then
                    // delivery person needs at least 30 minutes of breathing room to delivery
                    // could switch above to $current_hour+1 < $close to give an hour of breathing room
                    if($current_hour + 1 == $close) {
                        $isOpen = $current_minute < 30;
                    }
                    else {
                        $isOpen = true;
                    }
                }
            }
        }
        return $isOpen;
    }
}