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
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        $find_user = User::where('openid', $user['id'])->first();
        if(!\Session::has('user_id')) {
            if ( !$find_user ) {
                $data = [ 'wc_nickname' => $user[ 'name' ], 'head' => $user[ 'avatar' ], 'openid' => $user[ 'id' ] ];
                User::create($data);
                \Session::put('user_id', $user[ 'id' ]);
                \Session::put('head_pic', $user[ 'avatar' ]);
                \Session::put('nickname', $user[ 'nickname' ]);
            } else {
                \Session::put('phone', $find_user[ 'phone' ]);
                \Session::put('user_id', $find_user[ 'id' ]);
                \Session::put('head_pic', $find_user[ 'head' ]);
                \Session::put('nickname', $find_user[ 'wc_nickname' ]);
            }
        }
        return $next($request);
    }
}