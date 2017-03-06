<?php

namespace App;

use App\CustomTraits\IsAvailable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use IsAvailable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasRole($which_role)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $which_role)
                return true;
        }
        return false;
    }

    public function isAvailable($day, $time)
    {
        return $this->isAvailableOnDayAtTime($this, $day, $time);
    }

    public function issues() // TODO: double check that this is working
    {
        return $this->hasMany('App\Models\Issue', 'admin_id', 'id');
    }

    /* public function orders()
     {
         if($this->hasRole('admin') || $this->hasRole('courier')) {
             return $this->belongsToMany('App\Models\Order','couriers_orders',
                                         'courier_id','order_id')->withTimestamps();
         }
         return null;
     }*/

    /*
     * This is dynamic scoping. It allows you to encapsulate
     * dynamic query logic.
     * Helpful link: http://www.easylaravelbook.com/blog/2015/06/23/using-scopes-with-laravel-5/
     */
    /* public function scopeMemberType($query,$member_type)
     {
         $role = Role::where('name',$member_type)->first();
         return $query->where('role_id',$role->id);
     }*/

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'roles_users',
            'user_id', 'role_id');
    }
}
