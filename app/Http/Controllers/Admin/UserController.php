<?php

namespace App\Http\Controllers\Admin;

use App\Model\Integral;
use App\Model\User;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    /**
     * 前台用户管理
     * @普通用户列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->key;
        $value = $request->value;
        $where = [];
        switch($key){
            case 'wc_nickname':
                array_push($where, ['wc_nickname', 'like', "%$value%"]);
                break;
            case 'phone':
                array_push($where, ['phone', 'like', "%$value%"]);
                break;
        }
        array_push($where, ['type', 1]);
        $list = User::with(['extension'=>function($query){
            $query->select('id','wc_nickname');
        },'dealer'=>function($query){
            $query->select('id','wc_nickname');
        },'brand'])->where($where)->orderBy('created_at','desc')->paginate(15);

        foreach ($list as $key => $value) {
            $list[$key]['is_dealer'] = '';
            if($value['dealer_id'] != 0) {
                $dealer = User::with('dealer')->select('id', 'dealer_id')->where('id', $value[ 'dealer_id' ])->first();
                if(!empty($dealer->dealer)) {
                    $list[ $key ][ 'is_dealer' ] = 2;
                } else {
                    $list[ $key ][ 'is_dealer' ] = 1;
                }
            }
        }
        $menu = $this->menu; $active = $this->active;

        return view('admin.user.index',compact('list','menu','active'));
    }

    /**
     * 经销商列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dealerList(Request $request)
    {
        $key = $request->key;
        $value = $request->value;
        $where = [];
        switch($key){
            case 'wc_nickname':
                array_push($where, ['wc_nickname', 'like', "%$value%"]);
                break;
            case 'phone':
                array_push($where, ['phone', 'like', "%$value%"]);
                break;
        }
        array_push($where, ['type', 2]);
        $list = User::with(['dealer'=>function($query){
            $query->select('id','wc_nickname');
        },'brand','admin'=>function($query){
            $query->select('id','account');
        }])->where($where)->paginate(15);

        foreach ($list as $key =>$value) {
            $list[$key]['cmmission'] = app(Integral::class)->commission($value->id);
        }
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.user.dealer_index',compact('list','menu','active'));
    }

    /**
     * 成为经销商
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function be_dealer(User $user)
    {
        $update = $user->update(['type' => 2]);
        //该用户推广的所有用户更新关联关系
        $user->where('extension_id', $user->id)->update(['extension_id' => 0, 'dealer_id' => $user->id]);
        if($update){
            return redirect(route('admin.user'));
        }
    }

    /**
     * @title 查看佣金
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function seeIntegral($id)
    {
        $history_integral = number_format(Integral::where('user_id',$id)->sum('price'), 2);
        return response()->json(['history'=>$history_integral]);
    }

    /**
     * 设置佣金比例
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setIntegral(Request $request)
    {
        User::where('id',$request->id)->update(['integral_scale'=>$request->scale]);

        return redirect()->route('admin.user');
    }

    public function setMemberTime( Request $request )
    {
        User::where('id', $request->user_id)->update(['membership_time' => $request->membership_time]);

        return response()->json(['state' => 0, 'error' => '设置成功']);
    }
}
