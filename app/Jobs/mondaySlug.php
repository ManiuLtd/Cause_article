<?php

namespace App\Jobs;

use App\Model\Footprint;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class mondaySlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     * @param $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastweek = Carbon::parse('last week');
        $now = Carbon::parse('this week');
        //上周总文章数
        $complete_count =  UserArticles::where('uid',$this->user->id)->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
        //上周未分享文章数
        $uncommplete_count = UserArticles::where(['uid'=>$this->user->id,'isrs'=>0])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
        //上周文章被阅读数
        $read = Footprint::where(['uid'=>$this->user->id,'type'=>1])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
        //上周文章被分享数
        $share = Footprint::where(['uid'=>$this->user->id,'type'=>2])->whereBetween('created_at',[$lastweek->startOfDay(),$now->startOfDay()])->count();
        $app = new Application(config('wechat'));
        $msg = [
            "first"     => "新的一周，新的开始。上周头条效果跟踪：",
            "keyword1"  => $lastweek->toDateString() . "~" . $now->toDateString(),
            "keyword2"  => $complete_count,
            "keyword3"  => $uncommplete_count,
            "keyword4"  => $complete_count - $uncommplete_count,
            "keyword5"  => "被阅读了{$read} 次，分享{$share}次",
            "remark"    => "感谢您的使用。"
        ];
        template_message($app, $this->user->openid, $msg, config('wechat.template_id.monday_data_new'), config('app.url'));
    }
}
