<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/12/17
 * Time: 12:31 AM
 */

namespace App\CustomClasses\Stats;


class Stat
{
    private $name;
    private $stat_desc;

    public function __construct(string $name, $stat_desc)
    {
        $this->name = $name;
        $this->stat_desc = $stat_desc;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStatDesc()
    {
        return $this->stat_desc;
    }
}