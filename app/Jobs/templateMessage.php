<?php

namespace App\Jobs;

use EasyWeChat\Foundation\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class templateMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $openid;

    protected $message;

    protected $template_id;

    protected $url;

    /**
     * Create a new job instance.
     * @param $user
     * @return void
     */
    public function __construct($openid, $message, $template_id, $url)
    {
        $this->openid = $openid;

        $this->message = $message;

        $this->template_id = $template_id;

        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $app = new Application(config('wechat'));
        template_message($app, $this->openid, $this->message, $this->template_id, $this->url);
    }
}
