<?php

namespace App\CustomClasses\Stats;

use Carbon\Carbon;

abstract class Stats
{
    protected $start_time;
    protected $end_time;
    protected $previous_start_time;
    protected $previous_end_time;
    protected $stats;

    public function __construct()
    {
        $this->previous_start_time = Carbon::now()->subMonths(2);
        $this->previous_end_time = $this->start_time = Carbon::now()->subMonth();
        $this->end_time = Carbon::now();
    }

    public function changeStartTime()
    {
        // parse request input and turn it into a carbon object
        // make sure to update the previous too
    }

    public function changeEndTime()
    {
        // parse request input and turn it into a carbon object
        // make sure to update the previous too
    }

    public function getStats()
    {
        return $this->stats;
    }

    public abstract function computeStats();


}