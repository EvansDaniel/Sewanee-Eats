<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    public $timestamps = true;
    protected $table = "issues";

    public function admin()
    {
        return $this->belongsTo('App\Models\User', 'id', 'admin_id');
    }
}
