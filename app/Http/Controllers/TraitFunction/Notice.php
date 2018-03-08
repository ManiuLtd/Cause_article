<?php

namespace App\Http\Controllers\TraitFunction;

use App\Classes\Sms\SmsSender;
use App\Model\Order;
use App\Model\User;

trait Notice
{
    /**
     * 发送短信
     * @param $phone 发送手机号
     * @param $templId 短信模板id
     * @param $params 模板参数
     * @param $notice_msg
     * @return array
     */
    public function sms( $phone, $templId, $params, $notice_msg )
    {
        $appid = 1400058384;
        $appkey = "ea50aedaecf4b8821410bb4822b71d20";
        $sms = new SmsSender($appid, $appkey);
        $result = $sms->sendWithParam("86", $phone, $templId, $params);
        $arr = json_decode($result, true);
        //发送成功后记录到文件中
        if ( $arr[ 'errmsg' ] == 'OK' ) {
            $status = "发送成功【{$notice_msg}】";
            rwLog($phone, $status, 'notice_');
            return ['state' => 0, 'msg' => '发送成功'];
        } else {
            $status = "发送失败【{$notice_msg}】";
            rwLog($phone, $status, 'notice_');
            return ['state' => 401, 'msg' => '发送失败'];
        }
    }

    /**
     * @param bool $bool
     * @return array
     */
    public function extension($me_user, $bool = false) : array
    {
        $order = 0;
        $order_price = 0;
        $extension_user = 0;
        $extension_order = 0;
        $extension_order_price = 0;
        $users = User::select('id', 'integral_scale')->where('extension_id', $me_user->id)->when($bool, function($query) {
            return $query->whereDate('created_at', date('Y-m-d', time()));
        })->get();
        foreach ($users as $user) {
            $data = ['state' => 1, 'refund_state' => 0];
            $where = array_merge($data, ['uid' => $user->id]);
            $order += Order::where($where)->count();
            $price = Order::where($where)->sum('price') * ($me_user->integral_scale ? $me_user->integral_scale/100 : 0.3);
            $order_price += floor($price);

            $ex_users = User::select('id')->where('extension_id', $user->id)->when($bool, function ($query) {
                return $query->whereDate('created_at', date('Y-m-d', time()));
            })->get();
            $extension_user += count($ex_users);
            foreach ($ex_users as $e_user) {
                $where = array_merge($data, ['uid' => $e_user->id]);
                $extension_order += Order::where($where)->count();
                $price = Order::where($where)->sum('price') * 0.1;
                $extension_order_price += floor($price);
            }
        }

        return [count($users), $order, $order_price, $extension_user, $extension_order, $extension_order_price];
    }

}