<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 下午 1:20
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\TraitFunction\Notice;
use App\Model\Integral;
use App\Model\IntegralUse;
use App\Model\User;
use App\Model\UserAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExtensionController extends CommonController
{
    use Notice;

    /**
     * 推广中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $where = ['user_id' => \Session::get('user_id')];
        $tomorrow = Carbon::tomorrow();
        $today = Carbon::today();
        $today_integral = Integral::where($where)->whereBetween('created_at',[$today,$tomorrow])->sum('price');
        $tot_integral = Integral::where($where)->sum('price');
        $use_integral = IntegralUse::where($where)->sum('integral');
        $nu_integral = $tot_integral - $use_integral;

        return view('index.extension', compact('today_integral','tot_integral','use_integral','nu_integral'));
    }

    public function applyCash(  )
    {
        $user = User::with('user_account')->where('id', \Session::get('user_id'))->first();
        $where = ['user_id' => \Session::get('user_id')];
        $tot_integral = Integral::where($where)->sum('price');
        $use_integral = IntegralUse::where($where)->sum('integral');
        $nu_integral = $tot_integral - $use_integral;

        return view('index.cash', compact('user','nu_integral'));
    }

    public function bindAccount()
    {
        $user = User::with('user_account')->where('id', \Session::get('user_id'))->first();

        return view('index.account', compact('user'));
    }

    /**
     * 获取验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCode(Request $request)
    {
        $code = rand(100000,999999);
        $sms_code = $this->sms(\Session::get('phone'), 72236, [$code], '发送验证码');
        if($sms_code['state'] == 0) {
            if(isset($request->again)) {
                \Session::put('code', $code);
                return response()->json([ 'state' => 0, 'msg' => '已发送验证码' ]);
            } else {
                $arr = [
                    'code' => $code,
                    'type' => $request->type,
                    'name' => $request->name,
                    'card' => $request->card
                ];
                \Session::put($arr);
                return response()->json([ 'state' => 0, 'msg' => '已发送验证码' ]);
            }
        }
        return response()->json(['state' => 401, 'msg' => '发送验证码失败']);
    }

    /**
     * 验证码验证新增或修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCode( Request $request )
    {
        if($request->code == \Session::get('code')){
            \Session::forget('code');
            //保存账户信息
            $data = [
                'type' => \Session::get('type'),
                'name' => \Session::get('name'),
                'card' => \Session::get('card')
            ];
            if(isset($request->user_id)) {
                $save = UserAccount::where('user_id', $request->user_id)->update($data);
            } else {
                $data = array_merge($data, ['user_id' => \Session::get('user_id')]);
                $save = UserAccount::create($data);
            }

            if($save) {
                return response()->json(['state' => 0, 'msg' => '添加账户成功', 'url' => route('index.extension')]);
            }
        }
    }

    /**
     * 申请提现
     * @param Request $request
     * @param IntegralUse $integralUse
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoney( Request $request, IntegralUse $integralUse )
    {
        $integralUse->fill($request->all());
        $integralUse->user_id = \Session::get('user_id');
        $integralUse->created_at = date('Y-m-d H:i:s',time());
        $add_integral = $integralUse->save();
        if($add_integral) {
            return response()->json(['state' => 0, 'code' => 1, 'msg' => '申请提现成功']);
        }
        return response()->json(['state' => 401, 'code' => 0, 'msg' => '申请提现失败，请联系客服']);
    }

    public function getMoneyRecord( IntegralUse $integralUse )
    {
        $lists = $integralUse->get();
        return view('index.record', compact('lists'));
    }
}