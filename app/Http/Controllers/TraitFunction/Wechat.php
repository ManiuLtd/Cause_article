<?php

namespace App\Http\Controllers\TraitFunction;

use Wxpay\Wechat as wecaht;
use Illuminate\Support\Facades\Log;

trait Wechat
{
    public function ObtainUserInfo( $user )
    {
        //获取微信用户的基本信息判断是否已关注公众号
        $url_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . env('WECHAT_APP_ID') . "&secret=" . env('WECHAT_APP_SECRET');
        $ret_token = json_decode(wecaht::httpGet($url_token), true);
        $info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ret_token['access_token']}&openid={$user->openid}&lang=zh_CN";
        $user_info = json_decode(wecaht::httpGet($info_url), true);
        Log::debug($user_info);
        return $user_info;
    }
}