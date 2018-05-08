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
        // 每周爆文记录（每周一早上九点运行）
        $schedule->call(function () {
            foreach(User::where('subscribe', 1)->get() as $key => $value){
                dispatch(new mondaySlug($value));
            }
        })->weekly()->mondays()->at('9:00');

        // 搜索会员快过用户（期每天早上8点执行）
        $schedule->call(function () {
            for ($i = 1; $i <= 5; $i++) {
                $day = Carbon::now()->addDays($i)->toDateString();
                $users = User::whereDate('membership_time', $day)->get();
                foreach ($users as $value) {
                    dispatch(new everydaySlug($value, $i));
                }
            }
        })->dailyAt('8:00');

        // 完善信息（每小时执行一次）
        $schedule->call(function () {
            Log::info('每小时模板消息');
            $house = date('H');
            $begin_time = Carbon::create(null,null,null,$house-2,null,null);
            $end_time = Carbon::create(null,null,null,$house-1,null,null);
            $users = User::where(['phone' => '', 'subscribe' => 1])->whereBetween('created_at', [$begin_time, $end_time])->get();
            foreach ($users as $user) {
                $msg = [
                    "first"    => "您好，请您尽快完善个人信息，以便让更多的用户联系到您！",
                    "keyword1" => $user->wc_nickname,
                    "keyword2" => "手机号码、微信号、从业品牌等",
                    "keyword3" => date('Y-m-d'),
                    "remark"   => "点击详情立即完善→"
                ];
                dispatch(new templateMessage($user->openid, $msg, config('wechat.template_id.perfect_info'), route('index.user')));
            }
        })->hourly()->between('2:00', '23:00');

        // 会员过期通知消息
        $schedule->call(function () {
            $begin_time = Carbon::now()->toDateTimeString();
            $end_time = Carbon::now()->addMinute()->toDateTimeString();
            $users = User::whereBetween('membership_time', [$begin_time, $end_time])->get();
            foreach ($users as $user) {
                $msg = [
                    "first"    => array("尊敬的会员您好，您的会员已经过期", '#E91305'),
                    "keyword1" => array('商品信息：1个月会员/12个月会员/2年会员', '#E91305'),
                    "keyword2" => array(date('Y-m-d'), '#E91305'),
                    "remark"   => array("会员过期后将看不到访客信息，也无法展示自己的联系方式\n为了给您提供更好的服务，请点击详情立即去续费吧～", '#E91305')
                ];
                dispatch(new templateMessage($user->openid, $msg, config('wechat.template_id.member_time_overdue'), route('open_member')))->delay(Carbon::now()->addHours(12));
            }
        })->everyMinute();
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
