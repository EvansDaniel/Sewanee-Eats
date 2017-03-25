<?php

namespace App\Console;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // TODO: put this line in the cron tab of the server -> * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
        // https://laravel.com/docs/5.4/scheduling#introduction
        $schedule->call(function () {
            \Log::info("Here i am");
            // call at 2AM CT each day get users where role is courier or higher
            // adjust schedule to pop off the first array element in the
            // 2D available_times array and append an empty array element
            $this->updateScheduleForNextDay();
        })->dailyAt('02:00')->timezone('America/Chicago');
    }


    /**
     * Remove the time slots for today for each courier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateScheduleForNextDay()
    {
        // get all couriers
        $couriers = Role::where('name', 'courier')->first()->users;
        foreach ($couriers as $courier) {
            $avail_time = json_decode($courier->available_times);
            // pop of all of today's time slots for each courier
            $avail_time[Carbon::now($this->tz())->dayOfWeek] = [''];
            $courier->available_times = json_encode($avail_time);
            $courier->save();
        }
        return back();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected
    function commands()
    {
        require base_path('routes/console.php');
    }
}
