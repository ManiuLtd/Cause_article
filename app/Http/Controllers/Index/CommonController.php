<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8 0008
 * Time: 下午 5:04
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Model\Footprint;
use App\Model\User;
use Carbon\Carbon;
use Wxpay\Wechat;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Session()->has('user_id')) {
                $redirect = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                if (!$request->code) {
                    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . env('WECHAT_APP_ID') . '&redirect_uri=' . $redirect . '&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
                    header('Location: ' . $url);
                }else {
                    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . env('WECHAT_APP_ID') . '&secret=' . env('WECHAT_APP_SECRET') . '&code=' . $request->code . '&grant_type=authorization_code';
                    $res = json_decode(Wechat::httpGet($url), true);
                    $user = User::where('openid', $res['openid'])->first();
                    if (!$user) {
                        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $res['access_token'] . '&openid=' . $res['openid'] . '&lang=zh_CN';
                        $res = json_decode(Wechat::httpGet($url), true);
                        //把用户微信头像转换为base64保存到本地以防用户更换头像后不显示
//                        $head_dir = GrabImage($res['headimgurl'], $res['openid']);

                        $data = ['wc_nickname' => $res['nickname'], 'head' => $res['headimgurl'], 'openid' => $res['openid']];
                        //保存用户
                        $id = User::insertGetId($data);
                        $u = ['id' => $id, 'wc_nickname' => $res['nickname'], 'head' => $res['headimgurl'], 'phone' => ''];
                        $this->_loginToken($u);
                        return $next($request);
                    }
                    $this->_loginToken($user->toArray());
                }
            } else {
                $user = User::find(session()->get('user_id'))->toArray();
                define('UID', $user['id']);
                session()->put('user_id', $user['id']);
                session()->put('nickname', $user['wc_nickname']);
                session()->put('head_pic', $user['head']);
                session()->put('phone', $user['phone']);
            }
            return $next($request);
        });
    }

    public function _loginToken($user)
    {
//        define('UID', $user['id']);
        session()->put('user_id', $user['id']);
        session()->put('nickname', $user['wc_nickname']);
        session()->put('head_pic', $user['head']);
        session()->put('phone', $user['phone']);
    }
}