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

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //是否有新访客
            if ( Footprint::where([ 'uid' => session()->get('user_id'), 'new' => 1 ])->first() ) {
                \Session::put('newkf', 1);
            } else {
                \Session::forget('newkf');
            }
            return $next($request);
        });
    }
}