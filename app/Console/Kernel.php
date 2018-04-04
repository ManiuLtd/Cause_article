<?php

namespace App\Console;

use App\Jobs\everydaySlug;
use App\Jobs\mondaySlug;
use App\Model\User;
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
//        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每周一早上九点运行
        $schedule->call(function () {
            foreach(User::where('subscribe', 1)->get() as $key => $value){
                dispatch(new mondaySlug($value));
            }
        })->weekly()->mondays()->at('9:00');

        // 每天早上8点执行
        $schedule->call(function () {
            for ($i = 1; $i <= 5; $i++) {
                $day = Carbon::now()->addDays($i)->toDateString();
                $users = User::whereDate('membership_time', $day)->get();
                foreach ($users as $value) {
                    dispatch(new everydaySlug($value, $i));
                }
            }
        })->dailyAt('8:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
