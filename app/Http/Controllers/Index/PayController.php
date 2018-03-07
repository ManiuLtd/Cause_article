<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 上午 10:22
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Model\Integral;
use App\Model\User;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use App\Model\Order as MemberOrder;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
{
    /**
     * @title  下订单返回数据前端调起微信支付
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrder(Request $request)
    {
        //下订单
        $order = [
            'order_id'  =>  date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(100000, 999999),
            'uid'       =>  $request->uid,
            'price'     =>  $request->price,
            'title'     =>  $request->title,
            'type'      =>  $request->type,
            'created_at'=>  date('Y-m-d H:i:s')
        ];
        MemberOrder::create($order);
        return $this->wechatConfig($order);
    }

    /**
     * @title 调用easywechatSDK返回支付配置
     * @param $order 订单基本信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function wechatConfig(array $order)
    {
        $user = User::where('id',$order['uid'])->select('openid')->first();
        $options = config('wechat');
        $app = new Application($options);
        $payment = $app->payment;

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $order['title'],
            'detail'           => $order['title'],
            'out_trade_no'     => $order['order_id'],
            'total_fee'        => $order['price'] * 100, // 单位：分
            'notify_url'       => 'http://bw.eyooh.com/out_trade_no', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => "$user->openid", // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        $config = $payment->configForJSSDKPayment($result->prepay_id); // 返回数组
//        return $config;
        return response()->json(['state'=>0,'data'=>$config]);
    }

    /**
     * @title  支付完成回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Core\Exceptions\FaultException
     */
    public function outTradeNo()
    {
        $app = new Application(config('wechat'));
        $response = $app->payment->handleNotify(/**
         * @param $notify
         * @param $successful
         * @return bool|string
         */
            function( $notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = MemberOrder::where('order_id',$notify->out_trade_no)->first();
            \Log::info('订单支付成功，订单号:'.$notify->out_trade_no);
            if (!$order) { // 如果订单不存在
                return '如果订单不存在.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            // 用户是否支付成功
            if ($successful) {
                //用户会员加时间
                $pay_user = User::where('id',$order->uid)->first();
                //判断用户的会员时间是否过期
                if (Carbon::parse($pay_user->membership_time)->gt(Carbon::parse('now'))){
                    $time = Carbon::parse($pay_user->membership_time);
                } else {
                    $time = Carbon::now();
                }
                if($order->type == 1){
                    $pay_user->membership_time = $time->addMonth(1);
                } else if($order->type == 2){
                    $pay_user->membership_time = $time->addYear();
                } else if($order->type == 3){
                    $pay_user->membership_time = $time->addYears(2);
                }
                $pay_user->save();

                //所属员工和员工部门
                if($pay_user->admin_id && $pay_user->admin_type) {
                    $order->admin_id = $pay_user->admin_id;
                    $order->admin_type = $pay_user->admin_type;
                }

                //推广用户获得佣金
                if($pay_user->extension_id){
                    $app = new Application(config('wechat'));
                    $p_user = User::where('id', $pay_user->extension_id)->select('wc_nickname', 'openid', 'integral_scale', 'extension_id', 'admin_id', 'admin_type')->first();
                    $scale = $p_user->integral_scale != 0 ? $p_user->integral_scale : 30;
                    $price = number_format($order->price * ($scale / 100));
                    $data = [
                        'user_id'  =>  $pay_user->extension_id,
                        'price'    =>  $price,
                        'order_id' =>  $order->id
                    ];
                    Integral::create($data);
                    //所属员工和员工部门
                    if($p_user->admin_id && $p_user->admin_type) {
                        $order->admin_id = $p_user->admin_id;
                        $order->admin_type = $p_user->admin_type;
                    }
                    //推送【推荐成交通知】模板消息
                    $msg = [
                        "first"     => "尊敬的 $p_user->wc_nickname 你好，你推荐的客户已成交。",
                        "keyword1"  => $pay_user->wc_nickname,
                        "keyword2"  => date('Y-m-d',time()),
                        "keyword3"  => $price . '元',
                        "keyword4"  => $order->title,
                        "remark"    => "感谢您的推荐。"
                    ];
                    template_message($app, $p_user->openid, $msg, config('wechat.template_id.success_pay'), route('index.extension'));

                    if($p_user->extension_id) {
                        $pp_user = User::where('id', $p_user->extension_id)->select('openid', 'admin_id', 'admin_type')->first();
                        $price = number_format($order->price * 0.1);
                        $dealer_data = [
                            'user_id'  => $p_user->extension_id,
                            'price'    => $price,
                            'order_id' => $order->id
                        ];
                        Integral::create($dealer_data);
                        //所属员工和员工部门
                        if($pp_user->admin_id && $pp_user->admin_type) {
                            $order->admin_id = $pp_user->admin_id;
                            $order->admin_type = $pp_user->admin_type;
                        }
                        //推送【推荐成交通知】模板消息
                        $msg = [
                            "first"     => "尊敬的 $pp_user->wc_nickname 你好，你推荐的客户已成功推荐下级用户成交。",
                            "keyword1"  => $pay_user->wc_nickname,
                            "keyword2"  => date('Y-m-d',time()),
                            "keyword3"  => $price . '元',
                            "keyword4"  => $order->title,
                            "remark"    => "感谢您的推荐。"
                        ];
                        template_message($app, $pp_user->openid, $msg, config('wechat.template_id.success_pay'), route('index.extension'));
                    }
                }

                // 订单表修改为已经支付状态
                $order->state = 1;
                $order->pay_time = date('Y-m-d H:i:s');
            } else { // 用户支付失败
                $order->state = 2;
            }
            $order->save();
            return true; // 返回处理完成
        });
        return $response;
    }
}