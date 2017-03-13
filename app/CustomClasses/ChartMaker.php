<?php

namespace App\CustomClasses;


class ChartMaker
{

    private $labels;

    // Array of data for chart to show
    private $data;


    public function __construct($labels, $data)
    {
        $this->labels = $labels;
        $this->data = $data;
    }

    public function labels()
    {
        return $this->labels;
    }

    public function data()
    {
        return $this->data;
    }
}