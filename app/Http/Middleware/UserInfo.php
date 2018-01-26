<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 上午 9:13
 */

namespace App\Http\Middleware;

use App\Model\User;
use Closure;
use EasyWeChat\Foundation\Application;

class UserInfo
{
    /**
     * 处理传入的请求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!\Session::has('user_id')) {
            $user = session('wechat.oauth_user'); // 拿到授权用户资料
            $find_user = User::where('openid', $user['id'])->first();
            if ( !$find_user ) {
                $app = new Application(config('wechat'));
                $wechatUserInfo = $app->user->get($user->id);
                $data = [ 'wc_nickname' => $user[ 'name' ], 'head' => $user[ 'avatar' ], 'openid' => $user[ 'id' ], 'subscribe' => $wechatUserInfo['subscribe'] ];
                $add = User::create($data);
                session(['user_id' => $add->id, 'head_pic' => $user[ 'avatar' ], 'nickname' => $user[ 'nickname' ]]);
            } else {
                session(['user_id' => $find_user[ 'id' ], 'phone' => $find_user[ 'phone' ], 'head_pic' => $find_user[ 'head' ], 'nickname' => $find_user[ 'wc_nickname' ]]);
            }
        }
        return $next($request);
    }
}