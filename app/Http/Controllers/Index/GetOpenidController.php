<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/10 0010
 * Time: 上午 11:43
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;

class GetOpenidController extends Controller
{
    public function getOpenid()
    {
        $openid = session('wechat.oauth_user')['id'];

        return view('openid', compact('openid'));
    }
}