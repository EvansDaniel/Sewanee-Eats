<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPriceInfo extends Model
{
    protected $table = "order_price_info";

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}
