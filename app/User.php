<?php

namespace App;

use App\Contracts\Availability;
use App\CustomClasses\Availability\IsAvailable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Availability
{
    use Notifiable;
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

    public function courierInfo()
    {
        if ($this->hasRole('courier')) {
            return $this->hasOne('App\Models\CourierInfo', 'user_id', 'id');
        }
        return null;
    }

    public function hasRole($which_role)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $which_role)
                return true;
        }
        return false;
    }

    public function issues() // TODO: double check that this is working
    {
        return $this->hasMany('App\Models\Issue', 'admin_id', 'id');
    }

    // a user (that is an employee) has many shifts
    public function timeRanges()
    {
        if ($this->hasRole('admin') || $this->hasRole('courier') || $this->hasRole('manager')) {
            return $this->belongsToMany('App\Models\TimeRange',
                'time_ranges_users', 'user_id', 'time_range_id');
        }
        return null;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'roles_users',
            'user_id', 'role_id');
    }

    public function getAvailability()
    {
        return $this->timeRanges;
    }

    public function isOnShift()
    {
        $isAvail = new IsAvailable($this);
        // a courier accepts is sent order for there
        // entire shift even if there is very little time
        // left for it, they can only accept the shift during
        // there shift though
        return $isAvail->isAvailableNow();
    }
}
