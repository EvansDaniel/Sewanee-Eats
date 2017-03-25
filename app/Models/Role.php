<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";
    public function users()
    {
        return $this->belongsToMany('App\User', 'roles_users',
            'role_id', 'user_id');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('name', $type);
    }
}
