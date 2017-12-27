<?php

namespace App\Http\Controllers\TraitFunction;

use App\Model\User;
use EasyWeChat\Foundation\Application;

trait FunctionUser
{

    public function optionInviting($user, $request)
    {
        if ( $request->type == 1 ) {
            $image = $request->url;
        } elseif ( $request->type == 2 ) {
            // 保存本地图片
            $path = base64Toimg($request->url, 'inviting_qrcode');
            //待扩展-》如已生成的图片下次直接用原来的图片上传临时素材发送客服消息
            User::where('id', $user->id)->update([ 'extension_image' => $path[ 'path' ] ]);
            $image = $path[ 'path' ];
        }
        // 上传临时图片素材
        $app = new Application(config('wechat.wechat_config'));
        $temporary = $app->material_temporary;
        $ret = $temporary->uploadImage("../public_html/uploads/" . $image);

        //推送文本消息
        $this->extension_tyep($user->extension_type, $user->extension_num, $user->openid);
        //推送推广图片
        message($user->openid, 'image', $ret->media_id);
    }
    /**
     * 推送自己的推广状态
     * @param $type
     * @param $openid
     */
    public function extension_tyep( $type, $num, $openid )
    {
        switch ( $type ) {
            case '0':
                $num = 5 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，首次成功邀请5个好友使用可免费赠送5天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";

                return message($openid, 'text', $context);
                break;
            case '1':
                $num = 15 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请10个好友使用可免费赠送5天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '2':
                $num = 35 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请20个好友使用可免费赠送10天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '3':
                $num = 65 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请30个好友使用可免费赠送10天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '4':
                $num = 105 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请40个好友使用可免费赠送20天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '5':
                $context = "分享下图邀请你的朋友同事也来使用事业头条吧。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
        }
    }
}