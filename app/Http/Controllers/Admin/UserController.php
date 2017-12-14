<?php

namespace App\Http\Controllers\Admin;

use App\Model\Integral;
use App\Model\User;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    /********前台用户管理********/
    /**
     * Display a listing of the resource.
     * @用户列表
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
        if(!empty($request->type))  array_push($where, ['type', $request->type]);
        $list = User::with(['extension'=>function($query){
            $query->select('id','wc_nickname');
        },'dealer'=>function($query){
            $query->select('id','wc_nickname');
        }])->where($where)->paginate(15);
        return view('admin.user.index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * 成为经销商
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function be_dealer($id)
    {
        $update = User::where('id',$id)->update(['type'=>2]);
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
        $history_integral = number_format(Integral::where('uid',$id)->sum('price'), 2);
        return response()->json(['history'=>$history_integral]);
    }

    public function setIntegral(Request $request)
    {
        User::where('id',$request->id)->update(['integral_scale'=>$request->scale]);
        return redirect()->route('admin.user');
    }
}
