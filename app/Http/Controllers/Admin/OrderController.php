<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use App\Model\Order;
use App\Model\User;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class OrderController extends CommonController
{
    /**
     * 订单列表
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request, Order $order )
    {
        $where = [];
        switch ($request->key) {
            case 'wc_nickname':
                if($request->value) {
                    $where[ 'uid' ] = User::where($request->key, $request->value)->value('id');
                }
                break;
            case 'phone':
                if($request->value) {
                    $where[ 'uid' ] = User::where($request->key, $request->value)->value('id');
                }
                break;
        }
        $list = $order->with('user')->where($where)->orderBy('created_at', 'desc')->paginate(15);
        foreach ($list as $key => $value) {
            $list[$key]['brand_name'] = Brand::where('id', $value->user->brand_id)->value('name');
        }
        return view('admin.order.index',['list'=>$list, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    public function remark(Request $request, Order $order )
    {
        $order->remark = $request->remark;
        if($order->save()) {
            return response()->json(['state' => 200]);
        }
         return response()->json(['state' => 500]);
    }

    /**
     * 订单退款
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function refund( Request $request, Order $order )
    {
        $orderNo = $order->order_id;
        $refundNo = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $orderPrice = $order->price * 100;
        $refundPrice = $request->money * 100;

        $app = new Application(config('wechat.wechat_config'));
        $payment = $app->payment;
        $ret = $payment->refund($orderNo, $refundNo, $orderPrice, $refundPrice);
        if($ret['result_code'] == 'SUCCESS' && $ret['return_code'] == 'SUCCESS') {
            //更新订单的退款状态
            $order->refund_time = date('Y-m-d H:i:s', time());
            $order->refund_state = 1;
            $order->save();
            //回退用户会员时间
            $user = User::find($order->uid);
            if($order->type == 1) {
                $user->membership_time = Carbon::parse($user->membership_time)->subMonth();
            } else {
                $user->membership_time = Carbon::parse($user->membership_time)->subYear();
            }
            $user->save();

            return response()->json(['state' => 200, 'message' => '退款成功']);
        } else {
            return response()->json(['state' => 400, 'message' => $ret['err_code_des']]);
        }
    }

    /**
     * 订单报表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
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
        //前月初时间
        $before_last_month_time = Carbon::now()->addMonth(-2)->startOfMonth();

        /*---- 今日 ----*/
        $today_bw_time = [$today_time, $tomorrow_time];
        //注册
        $today['user_register'] = User::whereBetween('created_at', $today_bw_time)->count();
        //开通
        $today['membership'] = Order::where('state', 1)->whereBetween('created_at', [$today_time, $tomorrow_time])->count();
        //开通率
        $today['order_count'] = Order::whereBetween('created_at', $today_bw_time)->count();
        if($today['membership'] != 0 || $today['order_count'] != 0) {
            $today[ 'membership_rate' ] = ($today[ 'membership' ] / $today[ 'order_count' ]) * 100;
        } else {
            $today[ 'membership_rate' ] = 0;
        }
        //开通金额
        $today['order_money'] = Order::where('state', 1)->whereBetween('created_at', $today_bw_time)->sum('price');
        //退款
        $today['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $today_bw_time)->count();
        //退款金额
        $today['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $today_bw_time)->sum('price');
        /*---- 今日 ----*/

        /*---- 昨日 ----*/
        $yesterday_bw_time = [$yesterday_time, $today_time];
        //注册
        $yesterday['user_register'] = User::whereBetween('created_at', $yesterday_bw_time)->count();
        //开通
        $yesterday['membership'] = Order::where('state', 1)->whereBetween('created_at', $yesterday_bw_time)->count();
        //开通率
        $yesterday['order_count'] = Order::whereBetween('created_at', $yesterday_bw_time)->count();
        if($yesterday['membership'] != 0 || $yesterday['order_count'] != 0) {
            $yesterday[ 'membership_rate' ] = ($yesterday[ 'membership' ] / $yesterday[ 'order_count' ]) * 100;
        } else {
            $yesterday[ 'membership_rate' ] = 0;
        }
        //开通金额
        $yesterday['order_money'] = Order::where('state', 1)->whereBetween('created_at', $yesterday_bw_time)->sum('price');
        //退款
        $yesterday['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $yesterday_bw_time)->count();
        //退款金额
        $yesterday['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $yesterday_bw_time)->sum('price');
        /*---- 昨日 ----*/

        /*---- 同比 ----*/
        //注册
        $today_yesterday['user_register'] = $today['user_register'] - $yesterday['user_register'];
        //开通
        $today_yesterday['membership'] = $today['membership'] - $yesterday['membership'];
        //开通率
        $today_yesterday['order_count'] = $today['order_count'] - $yesterday['order_count'];
        if($today_yesterday['membership'] != 0 || $today_yesterday['order_count'] != 0) {
            $today_yesterday[ 'membership_rate' ] = ($today_yesterday[ 'membership' ] / $today_yesterday[ 'order_count' ]) * 100;
        } else {
            $today_yesterday[ 'membership_rate' ] = 0;
        }
        //开通金额
        $today_yesterday['order_money'] = $today['order_money'] - $yesterday['order_money'];
        //退款
        $today_yesterday['refund'] = $today['refund'] - $yesterday['refund'];
        //退款金额
        $today_yesterday['refund_money'] = $today['refund_money'] - $yesterday['refund_money'];
        /*---- 同比 ----*/

        /*---- 前日 ----*/
        $before_yesterday_bw_time = [$day_before_yesterday_time, $yesterday_time];
        //注册
        $before_yesterday['user_register'] = User::whereBetween('created_at', $before_yesterday_bw_time)->count();
        //开通
        $before_yesterday['membership'] = Order::where('state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //开通率
        $before_yesterday['order_count'] = Order::whereBetween('created_at', $before_yesterday_bw_time)->count();
        if($before_yesterday['membership'] != 0 || $before_yesterday['order_count'] != 0) {
            $before_yesterday[ 'membership_rate' ] = ($before_yesterday[ 'membership' ] / $before_yesterday[ 'order_count' ]) * 100;
        } else {
            $before_yesterday[ 'membership_rate' ] = 0;
        }
        //开通金额
        $before_yesterday['order_money'] = Order::where('state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->sum('price');
        //退款
        $before_yesterday['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->count();
        //退款金额
        $before_yesterday['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_yesterday_bw_time)->sum('price');
        /*---- 前日 ----*/

        /*---- 本月 ----*/
        $this_month_bw_time = [$start_month_time, $end_month_time];
        //注册
        $this_month['user_register'] = User::whereBetween('created_at', $this_month_bw_time)->count();
        //开通
        $this_month['membership'] = Order::where('state', 1)->whereBetween('created_at', $this_month_bw_time)->count();
        //开通率
        $this_month['order_count'] = Order::whereBetween('created_at', $this_month_bw_time)->count();
        if($this_month['membership'] != 0 || $this_month['order_count'] != 0) {
            $this_month[ 'membership_rate' ] = ($this_month[ 'membership' ] / $this_month[ 'order_count' ]) * 100;
        } else {
            $this_month[ 'membership_rate' ] = 0;
        }
        //开通金额
        $this_month['order_money'] = Order::where('state', 1)->whereBetween('created_at', $this_month_bw_time)->sum('price');
        //退款
        $this_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $this_month_bw_time)->count();
        //退款金额
        $this_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $this_month_bw_time)->sum('price');
        /*---- 本月 ----*/

        /*---- 上月 ----*/
        $last_month_bw_time = [$last_month_time, $start_month_time];
        //注册
        $last_month['user_register'] = User::whereBetween('created_at', $last_month_bw_time)->count();
        //开通
        $last_month['membership'] = Order::where('state', 1)->whereBetween('created_at', $last_month_bw_time)->count();
        //开通率
        $last_month['order_count'] = Order::whereBetween('created_at', $last_month_bw_time)->count();
        if($last_month['membership'] != 0 || $last_month['order_count'] != 0) {
            $last_month[ 'membership_rate' ] = ($last_month[ 'membership' ] / $last_month[ 'order_count' ]) * 100;
        } else {
            $last_month[ 'membership_rate' ] = 0;
        }
        //开通金额
        $last_month['order_money'] = Order::where('state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        //退款
        $last_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->count();
        //退款金额
        $last_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $last_month_bw_time)->sum('price');
        /*---- 上月 ----*/

        /*---- 前月 ----*/
        $before_last_month_bw_time = [$before_last_month_time, $last_month_time];
        //注册
        $before_last_month['user_register'] = User::whereBetween('created_at', $before_last_month_bw_time)->count();
        //开通
        $before_last_month['membership'] = Order::where('state', 1)->whereBetween('created_at', $before_last_month_bw_time)->count();
        //开通率
        $before_last_month['order_count'] = Order::whereBetween('created_at', $before_last_month_bw_time)->count();
        if($before_last_month['membership'] != 0 || $before_last_month['order_count'] != 0) {
            $before_last_month[ 'membership_rate' ] = ($before_last_month[ 'membership' ] / $before_last_month[ 'order_count' ]) * 100;
        } else {
            $before_last_month[ 'membership_rate' ] = 0;
        }
        //开通金额
        $before_last_month['order_money'] = Order::where('state', 1)->whereBetween('created_at', $before_last_month_bw_time)->sum('price');
        //退款
        $before_last_month['refund'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_last_month_bw_time)->count();
        //退款金额
        $before_last_month['refund_money'] = Order::where('refund_state', 1)->whereBetween('created_at', $before_last_month_bw_time)->sum('price');
        /*---- 前月 ----*/

        /*---- 总计 ----*/
        //注册
        $total['user_register'] = User::count();
        //开通
        $total['membership'] = Order::where('state', 1)->count();
        //开通率
        $total['order_count'] = Order::count();
        if($total['membership'] != 0 || $total['order_count'] != 0) {
            $total[ 'membership_rate' ] = ($total[ 'membership' ] / $total[ 'order_count' ]) *100;
        } else {
            $total[ 'membership_rate' ] = 0;
        }
        //开通金额
        $total['order_money'] = Order::sum('price');
        //退款
        $total['refund'] = Order::where('refund_state', 1)->count();
        //退款金额
        $total['refund_money'] = Order::where('refund_state', 1)->sum('price');
        /*---- 总计 ----*/
        $menu = $this->menu;
        $active = $this->active;
        $compact = compact('today','yesterday','today_yesterday','before_yesterday','this_month','last_month','before_last_month','total','menu','active');

        return view('admin/order/report',$compact);
    }
}