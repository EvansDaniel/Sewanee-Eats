<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/23/17
 * Time: 8:20 AM
 */

namespace App\Contracts;


interface HasItems
{
    // must be a CartItem
    public function items();
}