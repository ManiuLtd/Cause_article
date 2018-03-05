<?php

namespace App\Jobs;

use App\Model\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class extensionphoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $image_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $image_url)
    {
        $this->user = $user;

        $this->image_url = $image_url;
    }

    /**
     * 推送推广消息和图片
     */
    public function handle()
    {
        $path = base64Toimg($this->image_url, 'inviting_qrcode');
        $image = $path[ 'path' ];

        // 上传临时图片素材
        $app = new Application(config('wechat'));
        $temporary = $app->material_temporary;
        $file_path = config('app.image_real_path')."uploads/" . $image;
        $ret = $temporary->uploadImage($file_path);
        unlink($file_path);

        //推送推广图片
        message($this->user->openid, 'image', $ret->media_id);
    }
}
