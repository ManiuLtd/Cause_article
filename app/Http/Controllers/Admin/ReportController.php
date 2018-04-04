<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23 0023
 * Time: 上午 11:46
 */

namespace App\Http\Controllers\Admin;


use App\Model\Admin;
use App\Model\Article;
use App\Model\IntegralUse;
use App\Model\Order;
use App\Model\Report;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends CommonController
{

    public function index(Request $request)
    {
        $where = [];
        if(!empty($request->tyep)) $where['type'] = $request->type;
        if(!empty($request->value)){
            $key = $request->key;
            switch ($key) {
                case 'article':
                    $article = Article::where('title',$request->value)->select('id')->first();
                    if($article) $where['aid'] = $article->id;
                    break;
                case 'user':
                    $user = User::where('wc_nickname',$request->value)->select('id')->first();
                    if($user) $where['uid'] = $user->id;
                    break;
            }
        }
        $list = Report::with('article','user')->where($where)->orderBy('created_at','desc')->paginate(15);
        return view('admin.report.index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * 招商推广报表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function extensionReport(Request $request)
    {
        $time = explode(' - ',$request->date_range_picker);
        if(!empty($time[0])) {
            $tomorrow = Carbon::parse($time[1])->addDay();
            $tot_tomorrow = Carbon::parse($time[1])->addDay();
            $for_length = $tomorrow->day - Carbon::parse($time[0])->day;
        } else {
            $tomorrow = Carbon::parse('tomorrow');
            $tot_tomorrow = Carbon::parse('tomorrow');
            $for_length = 30;
        }

        if(has_menu($this->menu, '/admin/see_all')) {
            $extension = Admin::whereIn('gid', [14, 21])->get();
            $gid = true;
        } else {
            $extension = Admin::where('id', Auth::user()->id)->get();
            $gid = false;
        }
        app(Report::class)->report($extension, $gid, $tomorrow, $tot_tomorrow, $for_length, 1);
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.report.extension', compact('extension', 'menu', 'active'));
    }

    /**
     * 运营推广报表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operateReport(Request $request)
    {
        $time = explode(' - ',$request->date_range_picker);
        if(!empty($time[0])) {
            $tomorrow = Carbon::parse($time[1])->addDay();
            $tot_tomorrow = Carbon::parse($time[1])->addDay();
            $for_length = $tomorrow->day - Carbon::parse($time[0])->day;
        } else {
            $tomorrow = Carbon::parse('tomorrow');
            $tot_tomorrow = Carbon::parse('tomorrow');
            $for_length = 30;
        }

        if(has_menu($this->menu, '/admin/see_all')) {
            $extension = Admin::where('gid', 15)->get();
            $gid = true;
        } else {
            $extension = Admin::where('id', Auth::user()->id)->get();
            $gid = false;
        }

        app(Report::class)->report($extension, $gid, $tomorrow, $tot_tomorrow, $for_length, 2);
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.report.operate', compact('extension', 'menu', 'active'));
    }

    /**
     * 订单报表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
        $where = ['state' => 1, 'refund_state' => 0];
        //今天凌晨时间
        $today_time = Carbon::today();
        //昨日凌晨时间
        $yesterday_time = Carbon::yesterday();
        //明天凌晨时间
        $tomorrow_time = Carbon::tomorrow();
        //前天凌晨时间
        $day_before_yesterday_time = Carbon::yesterday()->subDay();
        //本月初时间
        $start_month_time = Carbon::now()->startOfMonth();
        //下月初时间
        $end_month_time = Carbon::now()->addMonth(1)->startOfMonth();
        //上月初时间
        $last_month_time = Carbon::now()->addMonth(-1)->startOfMonth();
        //上月今天的时间
        $last_month_day_time = Carbon::today()->subMonth();
        //前月初时间
        $before_last_month_time = Carbon::now()->addMonth(-2)->startOfMonth();

        /*---- 今日 ----*/
        $today_bw_time = [$today_time, $tomorrow_time];
        //访客
        $today['user_fk'] = User::where('phone', '')->whereBetween('created_at', $today_bw_time)->count();
        //注册
        $today['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $today_bw_time)->count();
        //订单数
        $today['order'] = Order::whereBetween('created_at', $today_bw_time)->count();
        //开通
        $today['membership'] = Order::where($where)->whereBetween('created_at', $today_bw_time)->count();
        //付费开通率
        $today['order_count'] = Order::whereBetween('created_at', $today_bw_time)->count();
        if($today['membership'] != 0 && $today['order_count'] != 0) {
            $today[ 'membership_rate' ] = ($today[ 'membership' ] / $today[ 'order_count' ]) * 100;
        } else {
            $today[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $today['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $today_bw_time)->count();
        if($today['membership'] != 0 && $today['user'] != 0) {
            $today[ 'user_membership_rate' ] = ($today[ 'membership' ] / $today[ 'user' ]) * 100;
        } else {
            $today[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $today['order_money'] = Order::where($where)->whereBetween('created_at', $today_bw_time)->sum('price');
        //提现
        $today['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $today_bw_time)->sum('integral');
        //退款
        $today['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $today_bw_time)->count();
        //退款金额
        $today['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $today_bw_time)->sum('price');
        /*---- 今日 ----*/

        /*---- 昨日 ----*/
        $yesterday_bw_time = [$yesterday_time, $today_time];
        //访客
        $yesterday['user_fk'] = User::where('phone', '')->whereBetween('created_at', $yesterday_bw_time)->count();
        //注册
        $yesterday['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $yesterday_bw_time)->count();
        //订单数
        $yesterday['order'] = Order::whereBetween('created_at', $yesterday_bw_time)->count();
        //开通
        $yesterday['membership'] = Order::where($where)->whereBetween('created_at', $yesterday_bw_time)->count();
        //开通率
        $yesterday['order_count'] = Order::whereBetween('created_at', $yesterday_bw_time)->count();
        if($yesterday['membership'] != 0 && $yesterday['order_count'] != 0) {
            $yesterday[ 'membership_rate' ] = ($yesterday[ 'membership' ] / $yesterday[ 'order_count' ]) * 100;
        } else {
            $yesterday[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $yesterday['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $yesterday_bw_time)->count();
        if($yesterday['membership'] != 0 && $yesterday['user'] != 0) {
            $yesterday[ 'user_membership_rate' ] = ($yesterday[ 'membership' ] / $yesterday[ 'user' ]) * 100;
        } else {
            $yesterday[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $yesterday['order_money'] = Order::where($where)->whereBetween('created_at', $yesterday_bw_time)->sum('price');
        //提现
        $yesterday['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $yesterday_bw_time)->sum('integral');
        //退款
        $yesterday['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $yesterday_bw_time)->count();
        //退款金额
        $yesterday['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $yesterday_bw_time)->sum('price');
        /*---- 昨日 ----*/

        /*---- 前日 ----*/
        $before_yesterday_bw_time = [$day_before_yesterday_time, $yesterday_time];
        //访客
        $before_yesterday['user_fk'] = User::where('phone', '')->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //注册
        $before_yesterday['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //订单数
        $before_yesterday['order'] = Order::whereBetween('created_at', $before_yesterday_bw_time)->count();
        //开通
        $before_yesterday['membership'] = Order::where($where)->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //开通率
        $before_yesterday['order_count'] = Order::whereBetween('created_at', $before_yesterday_bw_time)->count();
        if($before_yesterday['membership'] != 0 && $before_yesterday['order_count'] != 0) {
            $before_yesterday[ 'membership_rate' ] = ($before_yesterday[ 'membership' ] / $before_yesterday[ 'order_count' ]) * 100;
        } else {
            $before_yesterday[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $before_yesterday['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $before_yesterday_bw_time)->count();
        if($before_yesterday['membership'] != 0 && $before_yesterday['user'] != 0) {
            $before_yesterday[ 'user_membership_rate' ] = ($before_yesterday[ 'membership' ] / $before_yesterday[ 'user' ]) * 100;
        } else {
            $before_yesterday[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $before_yesterday['order_money'] = Order::where($where)->whereBetween('created_at', $before_yesterday_bw_time)->sum('price');
        //提现
        $before_yesterday['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->sum('integral');
        //退款
        $before_yesterday['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //退款金额
        $before_yesterday['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->sum('price');
        /*---- 前日 ----*/

        /*---- 本月 ----*/
        $this_month_bw_time = [$start_month_time, $end_month_time];
        //访客
        $this_month['user_fk'] = User::where('phone', '')->whereBetween('created_at', $this_month_bw_time)->count();
        //注册
        $this_month['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $this_month_bw_time)->count();
        //订单数
        $this_month['order'] = Order::whereBetween('created_at', $this_month_bw_time)->count();
        //开通
        $this_month['membership'] = Order::where($where)->whereBetween('created_at', $this_month_bw_time)->count();
        //开通率
        $this_month['order_count'] = Order::whereBetween('created_at', $this_month_bw_time)->count();
        if($this_month['membership'] != 0 && $this_month['order_count'] != 0) {
            $this_month[ 'membership_rate' ] = ($this_month[ 'membership' ] / $this_month[ 'order_count' ]) * 100;
        } else {
            $this_month[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $this_month['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $this_month_bw_time)->count();
        if($this_month['membership'] != 0 && $this_month['user'] != 0) {
            $this_month[ 'user_membership_rate' ] = ($this_month[ 'membership' ] / $this_month[ 'user' ]) * 100;
        } else {
            $this_month[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $this_month['order_money'] = Order::where($where)->whereBetween('created_at', $this_month_bw_time)->sum('price');
        //提现
        $this_month['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $this_month_bw_time)->sum('integral');
        //退款
        $this_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $this_month_bw_time)->count();
        //退款金额
        $this_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $this_month_bw_time)->sum('price');
        /*---- 本月 ----*/

        /*---- 上月 ----*/
        $last_month_bw_time = [$last_month_time, $start_month_time];
        //访客
        $last_month['user_fk'] = User::where('phone', '')->whereBetween('created_at', $last_month_bw_time)->count();
        //注册
        $last_month['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $last_month_bw_time)->count();
        //订单数
        $last_month['order'] = Order::whereBetween('created_at', $last_month_bw_time)->count();
        //开通
        $last_month['membership'] = Order::where($where)->whereBetween('created_at', $last_month_bw_time)->count();
        //开通率
        $last_month['order_count'] = Order::whereBetween('created_at', $last_month_bw_time)->count();
        if($last_month['membership'] != 0 && $last_month['order_count'] != 0) {
            $last_month[ 'membership_rate' ] = ($last_month[ 'membership' ] / $last_month[ 'order_count' ]) * 100;
        } else {
            $last_month[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $last_month['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $last_month_bw_time)->count();
        if($last_month['membership'] != 0 && $last_month['user'] != 0) {
            $last_month[ 'user_membership_rate' ] = ($last_month[ 'membership' ] / $last_month[ 'user' ]) * 100;
        } else {
            $last_month[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $last_month['order_money'] = Order::where($where)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        //提现
        $last_month['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('integral');
        //退款
        $last_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->count();
        //退款金额
        $last_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        /*---- 上月 ----*/

        /*---- 上月1号到上月的今天的数据 ----*/
        $last_month_bw_time = [$last_month_time, $last_month_day_time];
        //访客
        $this_last_month['user_fk'] = User::where('phone', '')->whereBetween('created_at', $last_month_bw_time)->count();
        //注册
        $this_last_month['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $last_month_bw_time)->count();
        //订单数
        $this_last_month['order'] = Order::whereBetween('created_at', $last_month_bw_time)->count();
        //开通
        $this_last_month['membership'] = Order::where($where)->whereBetween('created_at', $last_month_bw_time)->count();
        //开通率
        $this_last_month['order_count'] = Order::whereBetween('created_at', $last_month_bw_time)->count();
        if($this_last_month['membership'] != 0 && $this_last_month['order_count'] != 0) {
            $this_last_month[ 'membership_rate' ] = ($this_last_month[ 'membership' ] / $this_last_month[ 'order_count' ]) * 100;
        } else {
            $this_last_month[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $this_last_month['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $last_month_bw_time)->count();
        if($this_last_month['membership'] != 0 && $this_last_month['user'] != 0) {
            $this_last_month[ 'user_membership_rate' ] = ($this_last_month[ 'membership' ] / $this_last_month[ 'user' ]) * 100;
        } else {
            $this_last_month[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $this_last_month['order_money'] = Order::where($where)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        //提现
        $this_last_month['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('integral');
        //退款
        $this_last_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->count();
        //退款金额
        $this_last_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        /*---- 上月1号到上月的今天的数据 ----*/

        /*---- 前月 ----*/
        $before_last_month_bw_time = [$before_last_month_time, $last_month_time];
        //访客数
        $before_last_month['user_fk'] = User::where('phone', '')->whereBetween('created_at', $before_last_month_bw_time)->count();
        //注册
        $before_last_month['user_register'] = User::where('phone', '<>', '')->whereBetween('created_at', $before_last_month_bw_time)->count();
        //订单数
        $before_last_month['order'] = Order::whereBetween('created_at', $before_last_month_bw_time)->count();
        //开通
        $before_last_month['membership'] = Order::where($where)->whereBetween('created_at', $before_last_month_bw_time)->count();
        //开通率
        $before_last_month['order_count'] = Order::whereBetween('created_at', $before_last_month_bw_time)->count();
        if($before_last_month['membership'] != 0 && $before_last_month['order_count'] != 0) {
            $before_last_month[ 'membership_rate' ] = ($before_last_month[ 'membership' ] / $before_last_month[ 'order_count' ]) * 100;
        } else {
            $before_last_month[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $before_last_month['user'] = User::where('phone', '<>', '')->whereBetween('created_at', $before_last_month_bw_time)->count();
        if($before_last_month['membership'] != 0 && $before_last_month['user'] != 0) {
            $before_last_month[ 'user_membership_rate' ] = ($before_last_month[ 'membership' ] / $before_last_month[ 'user' ]) * 100;
        } else {
            $before_last_month[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $before_last_month['order_money'] = Order::where($where)->whereBetween('created_at', $before_last_month_bw_time)->sum('price');
        //提现
        $before_last_month['use_integral'] = IntegralUse::where('state', 1)->whereBetween('created_at', $before_last_month_bw_time)->sum('integral');
        //退款
        $before_last_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_last_month_bw_time)->count();
        //退款金额
        $before_last_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_last_month_bw_time)->sum('price');
        /*---- 前月 ----*/

        /*---- 总计 ----*/
        //访客
        $total['user_fk'] = User::where('phone', '')->count();
        //注册
        $total['user_register'] = User::where('phone', '<>', '')->count();
        //订单数
        $total['order'] = Order::count();
        //开通
        $total['membership'] = Order::where($where)->count();
        //开通率
        $total['order_count'] = Order::count();
        if($total['membership'] != 0 && $total['order_count'] != 0) {
            $total[ 'membership_rate' ] = ($total[ 'membership' ] / $total[ 'order_count' ]) *100;
        } else {
            $total[ 'membership_rate' ] = 0;
        }
        //创建开通率
        $total['user'] = User::where('phone', '<>', '')->count();
        if($total['membership'] != 0 && $total['user'] != 0) {
            $total[ 'user_membership_rate' ] = ($total[ 'membership' ] / $total[ 'user' ]) * 100;
        } else {
            $total[ 'user_membership_rate' ] = 0;
        }
        //开通金额
        $total['order_money'] = Order::where($where)->sum('price');
        //提现
        $total['use_integral'] = IntegralUse::where('state', 1)->sum('integral');
        //退款
        $total['refund'] = Order::where('refund_state', 1)->count();
        //退款金额
        $total['refund_money'] = Order::where('refund_state', 1)->sum('price');
        /*---- 总计 ----*/
        $menu = $this->menu;
        $active = $this->active;
        $compact = compact('today','yesterday','before_yesterday','this_month','last_month','this_last_month','before_last_month','total','menu','active');

        return view('admin.report.order_report',$compact);
    }
}