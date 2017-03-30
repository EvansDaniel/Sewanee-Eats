<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierInfo extends Model
{
    protected $table = "courier_info";
    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
