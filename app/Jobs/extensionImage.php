<?php

namespace App\Jobs;

use App\Model\User;
use EasyWeChat\Foundation\Application;
use App\Http\Controllers\TraitFunction\FunctionUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class extensionImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FunctionUser;

    protected $type;

    protected $user;

    protected $image_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $type, $image_url)
    {
        $this->user = $user;

        $this->type = $type;

        $this->image_url = $image_url;
    }

    /**
     * 推送推广消息和图片
     */
    public function handle()
    {
        if ( $this->type == 1 ) {
            $image = $this->image_url;
        } elseif ( $this->type == 2 ) {
            // 保存本地图片
            $path = base64Toimg($this->image_url, 'inviting_qrcode');
            User::where('id', $this->user->id)->update([ 'extension_image' => $path[ 'path' ] ]);
            $image = $path[ 'path' ];
        }
        // 上传临时图片素材
        $app = new Application(config('wechat'));
        $temporary = $app->material_temporary;
        $ret = $temporary->uploadImage(config('app.image_real_path')."uploads/" . $image);

        //推送文本消息
        $this->extension_tyep($this->user->extension_type, $this->user->extension_num, $this->user->openid);
        //推送推广图片
        message($this->user->openid, 'image', $ret->media_id);
    }
}
