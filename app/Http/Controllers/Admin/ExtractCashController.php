<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 上午 11:15
 */

namespace App\Http\Controllers\Admin;


use App\Model\IntegralUse;
use Illuminate\Http\Request;

class ExtractCashController extends CommonController
{
    /**
     * 提现列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = IntegralUse::with('user')->paginate(15);
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.extract_cash.index', compact('list','menu','active'));
    }

    public function remark(Request $request, IntegralUse $integralUse)
    {
        $integralUse->update($request->all());

        return response()->json(['state' => 0]);
    }

    public function complete( IntegralUse $integralUse )
    {
        $integralUse->update(['state' => 1, 'over_at' => date('Y-m-d H:i:s', time())]);
        return redirect()->route('admin.extract_cash');
    }
}