<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/25/17
 * Time: 1:03 AM
 */

namespace App\CustomClasses\Schedule;


use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\Courier\CourierTypes;
use App\CustomTraits\HandlesTimeRanges;
use App\Models\TimeRange;
use App\User;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class Shift
{
    use HandlesTimeRanges;

    protected $shift;
    protected $manager;
    protected $couriers;

    public function __construct(TimeRange $shift = null)
    {
        $this->shift = $shift;
        $this->manager = $this->manager();
        $this->couriers = $this->couriers();
    }

    public function manager()
    {
        $manager = null;
        if (!empty($this->shift)) {
            foreach ($this->shift->users as $worker) {
                if ($worker->hasRole('manager')) {
                    $manager = $worker;
                }
            }
        }
        return $manager;
    }

    // prints the internal TimeRange

    public function couriers()
    {
        $couriers = [];
        if (!empty($this->shift)) {
            foreach ($this->shift->users as $worker) {
                if ($worker->hasRole('manager')) {
                    continue;
                }
                $couriers[] = $worker;
            }
        }
        return $couriers;
    }

    /**
     * @param $courier_type integer CourierTypes constant
     * @return string the translated courier type
     */
    public static function getCourierType($courier_type)
    {
        if ($courier_type == CourierTypes::DRIVER) {
            return 'Driver';
        }
        if ($courier_type == CourierTypes::BIKER) {
            return 'Biker';
        }
        if ($courier_type == CourierTypes::WALKER) {
            return 'Walker';
        }
        throw new InvalidArgumentException('Invalid courier type given. Must be a constant from the CourierTypes class');
    }

    public static function onDemandIsAvailable()
    {
        $shift_now = Shift::now();
        $shift = new Shift($shift_now);
        return !empty($shift_now) && $shift->hasCouriersAssigned();
    }

    /**
     * Gets the current shift
     * @return TimeRange|null
     */
    public static function now()
    {
        $shifts = TimeRange::ofType(TimeRangeType::SHIFT)->get();
        foreach ($shifts as $shift) {
            if (IsAvailable::nowIsBetweenOrEqualToTimeRange($shift, 30)) {
                return $shift;
            }
        }
        return null;
    }

    public function hasCouriersAssigned()
    {
        return !empty($this->couriers);
    }

    public function getCurrentShifts()
    {
        $shift_time_ranges = TimeRange::where('time_range_type', TimeRangeType::SHIFT)
            ->orderBy('start_dow', 'ASC')->orderBy('start_hour', 'ASC')
            ->orderBy('start_min', 'ASC')->get();
        $shifts = [];
        foreach ($shift_time_ranges as $shift_time_range) {
            $shifts[] = new Shift($shift_time_range);
        }
        return $shifts;
    }

    public function __toString()
    {
        return $this->shift->__toString();
    }

    public function removeWorkerFromShift($courier_id)
    {
        $this->shift->users()->detach($courier_id);
    }

    public function getShifts($dow)
    {
        $shift_time_ranges = $this->getTimeRangesByDay($dow, TimeRangeType::SHIFT);
        return $this->convertTimeRangeToShifts($shift_time_ranges);
    }

    private function convertTimeRangeToShifts($time_ranges)
    {
        if (is_a($time_ranges, 'Illuminate\Database\Eloquent\Collection')) {
            $shifts = [];
            foreach ($time_ranges as $shift_time_range) {
                $shifts[] = new Shift($shift_time_range);
            }
            return $shifts;
        } else {
            return new Shift($time_ranges);
        }
    }

    public function isValidShift()
    {
        return $this->validShift($this);
    }

    public function getUnassignedManagers()
    {
        if (!empty($this->getManager())) {
            return null;
        }
        return User::ofType('manager');
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function getUnassignedCouriers()
    {
        $couriers = User::ofType('courier');
        $unassigned = [];
        $shift_workers = $this->getCouriers();
        $shift_worker_ids = [];
        foreach ($shift_workers as $shift_worker) {
            $shift_worker_ids[] = $shift_worker->id;
        }
        foreach ($couriers as $courier) {
            if (!in_array($courier->id, $shift_worker_ids)) {
                $unassigned[] = $courier;
            }
        }
        return $unassigned;
    }

    public function getCouriers()
    {
        return $this->couriers;
    }

    public function getDayDateTimeString()
    {
        return $this->shift->getDayDateTimeString();
    }

    // at most one manager per shift

    /**
     * @param $user_id
     * @param $courier_type integer the type of courier the courier will be
     * for this shift
     * @return int
     */
    public function assignWorker($user_id, $courier_type)
    {
        $user = User::find($user_id);
        // shift has a manager and only one manager for a shift
        if ($this->hasManager() && $user->hasRole('manager')) {
            return -1;
        }
        \Log::info($courier_type);
        $this->shift->users()->save($user, ['courier_type' => $courier_type]);
        return 0;
    }

    // gets the current shift as an instance

    public function hasManager()
    {
        return !empty($this->manager);
    }

    public function getStartDay()
    {
        return $this->shift->start_dow;
    }

    public function getEndDay()
    {
        return $this->shift->end_dow;
    }

    public function getStartMin()
    {
        return $this->shift->start_min;
    }

    public function getEndMin()
    {
        return $this->shift->end_min;
    }

    public function getStartHour()
    {
        return $this->shift->start_hour;
    }

    public function getEndHour()
    {
        return $this->shift->end_hour;
    }

    public function getId()
    {
        return $this->shift->id;
    }
}