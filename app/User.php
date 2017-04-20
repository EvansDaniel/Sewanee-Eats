<?php

namespace App;

use App\Contracts\Availability;
use App\Contracts\ResourceTimeRange;
use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\Schedule\Shift;
use App\Models\Role;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Availability, ResourceTimeRange
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

    public static function ofType($role_type)
    {
        $role_type = Role::ofType($role_type)->first();
        if (empty($role_type)) {
            return null;
        }
        return $role_type->users;
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'couriers_orders',
            'courier_id', 'order_id')->withPivot('courier_payment')->withTimestamps();
    }

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

    // a user (that is an employee) has many shifts

    public function issues() // TODO: double check that this is working
    {
        return $this->hasMany('App\Models\Issue', 'admin_id', 'id');
    }

    public function timeRanges()
    {
        if ($this->hasRole('admin') || $this->hasRole('courier') || $this->hasRole('manager')) {
            return $this->belongsToMany('App\Models\TimeRange',
                'time_ranges_users', 'user_id', 'time_range_id')
                ->withPivot('courier_type');
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

    /**
     * @return string the courier type converted to a string
     */
    public function getCourierType()
    {
        if (empty($this->pivot)) {
            throw new InvalidArgumentException('The user must be retrieved with the pivot TimeRangesUsers table and column courier_type');
        }
        return Shift::getCourierType($this->pivot->courier_type);
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

    /**
     * @return integer the extra time to check before it actually closes
     * i.e. the cushion period
     */
    public function getExtraTime()
    {
        return 30; // 30 minutes before end of shift
    }

    public function getResourceTimeRangesByDay($dow)
    {
        // TODO: Implement getResourceTimeRangesByDay() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTimeRangeType()
    {
        return TimeRangeType::SHIFT;
    }
}
