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

class everydaySlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $day;

    /**
     * Create a new job instance.
     * @param $user
     * @return void
     */
    public function __construct($user, $day)
    {
        $this->user = $user;

        $this->day = $day;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info($this->user->toarray());
        $app = new Application(config('wechat'));
        $msg = [
            "first"     => "{$this->user->wc_nickname}您好，您的事业爆文名片功能，还有【{$this->day}】天到期，为了文章都带上您的头像、姓名和电话，防止流失重要顾客，请及时升级！",
            "name"      => '事业爆文会员',
            "expDate"   => $this->user->membership_time,
            "remark"    => "点【这里】升级>>"
        ];
        template_message($app, $this->user->openid, $msg, config('wechat.template_id.every_day'), route('open_member'));
    }
}
