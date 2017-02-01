<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{

    protected $table = "restaurants";


    // has many menu items
    public function menuItems()
    {
        return $this->hasMany('App\Models\MenuItem',
            'restaurant_id',
            'id');
    }
}
