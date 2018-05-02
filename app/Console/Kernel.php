<?php

namespace App\Console;

use App\Jobs\everydaySlug;
use App\Jobs\mondaySlug;
use App\Jobs\templateMessage;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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

        // 每小时执行一次
//        $schedule->call(function () {
//            Log::info('每小时模板消息');
//            $house = date('H');
//            $begin_time = Carbon::create(null,null,null,$house-2,null,null);
//            $end_time = Carbon::create(null,null,null,$house-1,null,null);
//            $users = User::where(['phone' => '', 'subscribe' => 1])->whereBetween('created_at', [$begin_time, $end_time])->get();
//            foreach ($users as $user) {
//                $msg = [
//                    "first"    => "您好，请您尽快完善个人信息，以便让更多的用户联系到您！",
//                    "keyword1" => $user->wc_nickname,
//                    "keyword2" => "手机号码、微信号、从业品牌等",
//                    "keyword3" => date('Y-m-d'),
//                    "remark"   => "点击详情立即完善→"
//                ];
//                dispatch(new templateMessage($user->openid, $msg, config('wechat.template_id.perfect_info'), route('index.user')));
//            }
//        })->hourly();
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
