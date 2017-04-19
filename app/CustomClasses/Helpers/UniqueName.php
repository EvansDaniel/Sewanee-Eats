<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 12:27 AM
 */

namespace App\CustomClasses\Helpers;


class UniqueName
{
    protected $name;
    protected $names;

    /**
     * UniqueName constructor.
     * @param string $name
     */
    public function __construct($names, string $name)
    {
        $this->names = $names;
        $this->name = $name;
    }

    /**
     * Precondition: Each name in $this->names must be of the form "string.number" or just "string"
     * If this is not met, the outcome is undefined
     * @return string a unique name of the form "$this->name.number"
     */
    public function getUniqueName()
    {
        $largest_num = 0;
        $found = false;
        foreach ($this->names as $name) {
            $name_array = explode(".", $name);
            if ($this->name == $name_array[0]) {
                $found = true;
                if (count($name_array) == 1) continue; // if we found the start_name but no number continue to next
                // the name contains a number so get the max
                $largest_num = max((int)$name_array[1], $largest_num);
            }
        }
        if ($largest_num == 0 && !$found) { // name is unique
            return $this->name;
        }
        $largest_num++;
        return $this->name . "." . $largest_num;
    }
}