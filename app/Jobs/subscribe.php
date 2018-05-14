<?php

namespace App\Jobs;

use App\Model\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class subscribe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $openid;

    protected $message;

    /**
     * Create a new job instance.
     * @param $openid
     * @param $message
     */
    public function __construct($openid, $message)
    {
        $this->openid = $openid;

        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Core\Exceptions\RuntimeException
     */
    public function handle()
    {
        if(User::where(['openid' => $this->openid])->value('subscribe')) {

            $app = new Application(config('wechat'));

            $app->staff->message($this->message)->to($this->openid)->send();
        }
    }
}
