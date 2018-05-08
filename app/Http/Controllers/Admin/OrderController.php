<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\AdminDistribution;
use App\Model\Brand;
use App\Model\Integral;
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
    public function index( Request $request )
    {
        $where = [];
        $wherein = [];
        switch ($request->key) {
            case 'wc_nickname':
                if($request->value) {
                    $uid = User::where($request->key, $request->value)->get()->pluck('id');
                    $wherein = $uid;
                }
                break;
            case 'phone':
                if($request->value) {
                    $uid = User::where($request->key, $request->value)->get()->pluck('id');
                    $wherein = $uid;
                }
                break;
        }
        if($request->state) $where['state'] = $request->state;
        if($wherein) {
            $list = Order::with('user.brand')->where($where)->whereIn('uid', $wherein)->orderBy('id', 'desc')->paginate(25);
        } else {
            $list = Order::with('user.brand')->where($where)->orderBy('id', 'desc')->paginate(25);
        }
        $new = $list->groupBy('uid');
        $arr = [];
        foreach ($new as $value) {
            $arr[] = $value->unique('uid');
        }

        return view('admin.order.index',['list'=>$list, 'v'=>$arr, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * 未支付订单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unPay( Request $request )
    {
        $where = [];
        $wherein = [];
        switch ($request->key) {
            case 'wc_nickname':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
            case 'phone':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
        }
        $where['state'] = 0;
        if($wherein) {
            $list = Order::with('user', 'dis')->where($where)->whereIn('uid', $wherein)->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $list = Order::with('user', 'dis')->where($where)->orderBy('created_at', 'desc')->paginate(15);
        }
        foreach ($list as $key => $value) {
            $list[$key]['brand_name'] = Brand::where('id', $value->user->brand_id)->value('name');
            if($value->distribution) $list[$key]['admin'] = Admin::where('id', $value->dis->admin_id)->value('account');
        }

        $new = $list->groupBy('uid');
        $arr = [];
        foreach ($new as $value) {
            $arr[] = $value->unique('uid');
        }

        $v = $arr;

        $menu = $this->menu;
        $active = $this->active;

        return view('admin.order.unpay', compact('list', 'v', 'admin', 'menu', 'active'));
    }

    public function pay( Request $request )
    {
        $where = [];
        $wherein = [];
        switch ($request->key) {
            case 'wc_nickname':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
            case 'phone':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
        }
        array_push($where, ['state', 1]);
        array_push($where, ['refund_state', 0]);
        if($wherein) {
            $list = Order::with('user.extension', 'dis')->where($where)->whereIn('uid', $wherein)->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $list = Order::with('user.extension', 'dis')->where($where)->orderBy('created_at', 'desc')->paginate(15);
        }
        foreach ($list as $key => $value) {
            $list[$key]['brand_name'] = Brand::where('id', $value->user->brand_id)->value('name');
//            if($value->distribution) $list[$key]['admin'] = Admin::where('id', $value->dis->admin_id)->value('account');
        }

        $admin = Admin::where('gid', 13)->get();
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.order.pay', compact('list', 'admin', 'menu', 'active'));
    }

    /**
     * 退款列表
     * @param Order $order
     */
    public function refundList( Request $request, Order $order )
    {
        $where = [];
        $wherein = [];
        switch ($request->key) {
            case 'wc_nickname':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
            case 'phone':
                if($request->value) {
                    $wherein = User::where($request->key, $request->value)->get()->pluck('id');
                }
                break;
        }
        $where['refund_state'] = 1;
        if($wherein) {
            $list = $order->with('user')->where($where)->whereIn('uid', $wherein)->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $list = $order->with('user')->where($where)->orderBy('created_at', 'desc')->paginate(15);
        }
        foreach ($list as $key => $value) {
            $list[$key]['brand_name'] = Brand::where('id', $value->user->brand_id)->value('name');
        }
        return view('admin.order.refund',['list'=>$list, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * 分配订单
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function distribution( Request $request )
    {
        foreach ($request->order_id as $value) {
            $data = [
                'order_id' => $value,
                'admin_id' => $request->admin_id,
                'type' => $request->type,
                'created_at' => date('Y-m-d H:i:s', time())
            ];
            $add = AdminDistribution::create($data);
            Order::where('id', $value)->update(['distribution' => $add->id]);
        }
        if($request->type == 1){
            $route = 'order.unpaylist';
        } else {
            $route = 'order.paylist';
        }
        return redirect()->route($route);
    }

    /**
     * 订单备注
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function remark(Request $request, Order $order )
    {
        $order->remark = $request->remark;
        if($order->save()) {
            return response()->json(['state' => 200]);
        }
         return response()->json(['state' => 500]);
    }

    /**
     * 删除订单
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete( Order $order )
    {
        $order->delete();
        return redirect()->route('order_list.index');
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

        $app = new Application(config('wechat'));
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
            } elseif($order->type == 2) {
                $user->membership_time = Carbon::parse($user->membership_time)->subYear();
            } elseif($order->type == 3) {
                $user->membership_time = Carbon::parse($user->membership_time)->subYears(2);
            }
            $user->save();

            Integral::where('order_id', $order->id)->update(['state' => 2]);

            return response()->json(['state' => 200, 'message' => '退款成功']);
        } else {
            return response()->json(['state' => 400, 'message' => $ret['err_code_des']]);
        }
    }
}