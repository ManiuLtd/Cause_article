<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 上午 9:13
 */

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use Carbon\Carbon;

class Member
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
        $shiptime = User::where('id',session()->get('user_id'))->select('membership_time')->first();
        if (Carbon::parse('now')->gt(Carbon::parse($shiptime->membership_time))) {
            return redirect(route('open_member'));
        }

        return $next($request);
    }
}