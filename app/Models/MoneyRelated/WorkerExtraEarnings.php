<?php

namespace App\Models\MoneyRelated;

use Illuminate\Database\Eloquent\Model;

class WorkerExtraEarnings extends Model
{
    protected $guarded = [];

    public function worker()
    {
        return $this->belongsTo('App\User', 'worker_id', 'id');
    }
}
