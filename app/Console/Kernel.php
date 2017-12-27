<?php

namespace App\Console;

use App\Model\Footprint;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
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
        // 每周一的下午一点钟运行
        $schedule->call(function () {
            $lastweek = Carbon::parse('last week');
            $now = Carbon::parse('this week');
            foreach(User::where('subscribe',1)->get() as $key => $value){
                //上周总文章数
                $complete_count =  UserArticles::where('uid',$value->id)->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
                //上周未分享文章数
                $uncommplete_count = UserArticles::where(['uid'=>$value->id,'isrs'=>0])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
                //上周文章被阅读数
                $read = Footprint::where(['uid'=>$value->id,'type'=>1])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
                //上周文章被分享数
                $share = Footprint::where(['uid'=>$value->id,'type'=>2])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
                $app = new Application(config('wechat.wechat_config'));
                $msg = [
                    "first"     => "新的一周，新的开始。上周头条效果跟踪：",
                    "keyword1"  => $lastweek->toDateString() . "~" . $now->toDateString(),
                    "keyword2"  => $complete_count,
                    "keyword3"  => $uncommplete_count,
                    "keyword4"  => $complete_count - $uncommplete_count,
                    "keyword5"  => "被阅读了 $read 次，分享 $share 次",
                    "remark"    => "感谢您的使用。"
                ];
                template_message($app, $value->openid, $msg, 'p_iI93KrqmvwbPDxFXaaIDBGqciPNYv63G1FTQ0zgV8','http://bw.eyooh.com');
            }
        })->weekly()->mondays()->at('9:00');
        // 每分钟执行一次
//        $schedule->call(function () {
//            User::where('id',4)->increment('extension_num', 1);
//        })->everyMinute();
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
