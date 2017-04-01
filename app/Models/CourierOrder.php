<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourierOrder extends Model
{
    use SoftDeletes;
    protected $table = "couriers_orders";
    protected $dates = ['deleted_at'];

    /**
     * @param $order_id
     * @return mixed all assignments to the order with given
     * $order_id including trashed ones (previously removed
     * b/c of some reason)
     */
    public function getAllAssignmentsToOrder($order_id)
    {
        return CourierOrder::withTrashed()
            ->where('order_id', $order_id)->get();
    }
}
