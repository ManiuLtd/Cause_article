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
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //是否有新访客
            if ( Footprint::where([ 'uid' => session('user_id'), 'new' => 1 ])->first() ) {
                \Session::put('newkf', 1);
            } else {
                \Session::forget('newkf');
            }

            $user = User::where('id', session('user_id'))->first();
            if(Cache::has($user->openid) && $user->subscribe && !$user->extension_id) {
                $cache = Cache::get($user->openid);
                $user->extension_id = $cache['extension_id'];
                $user->admin_id = $cache['admin_id'];
                $user->admin_type = $cache['admin_type'];
                $user->save();
            }

            return $next($request);
        });
    }
}