<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\Order;
use App\Model\Sale;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServerController extends CommonController
{
    /**
     * 售前总列表
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preSaleIndex( Request $request, User $user)
    {
        $where = [
            ['membership_time', '<', Carbon::now()->toDateTimeString()],
            ['phone', '<>', '']
        ];

        $key = $request->key;
        $value = $request->value;
        switch($key){
            case 'wc_nickname':
                array_push($where, ['wc_nickname', 'like', "%$value%"]);
                break;
            case 'phone':
                array_push($where, ['phone', 'like', "%$value%"]);
                break;
        }

        $users = $user->has('orderList')->with('sale.admin')->where($where)->orderBy('id', 'desc')->paginate(15);

        $admin = Admin::whereIn('gid', [12, 23])->get();

        $menu = $this->menu;
        $active = $this->active;

        return view('admin.sale.index', compact('menu', 'active', 'users', 'admin'));
    }

    /**
     * 个人分配的售前资源
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preSale()
    {
        $sales = Sale::with('user.brand','user.extension')->where('admin_id', \Auth::id())->paginate(15);

        $menu = $this->menu;
        $active = $this->active;

        return view('admin.sale.pre_sale', compact('menu', 'active', 'sales'));
    }

    public function afterSale()
    {

    }

    /**
     * 分配资源
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function preDistribution(Request $request)
    {
        foreach ($request->user_id as $user) {
            $sale = Sale::create(['admin_id' => $request->admin_id, 'user_id' => $user, 'type' => $request->type]);
            User::where('id', $user)->update(['sale_id' => $sale->id]);
        }

        return redirect()->back();
    }

    /**
     * 服务标记
     * @param Request $request
     * @param Sale $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function service( Request $request, Sale $sale)
    {
        if(!$sale->service_at) {
            $service_at = Carbon::now()->toDateTimeString();
        } else {
            $service_at = $sale->service_at;
        }
        $sale->update(['remark' => $request->remark, 'service_at' => $service_at]);

        return response()->json(['state' => Response::HTTP_CREATED]);
    }

    /**
     * 业绩订单列表
     * @param $pay_at  支付时间
     * @param $admin_id  售前员工id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preOrder( $pay_at, $admin_id )
    {
        $where = [['state' => 1]];
        if($admin_id) {
            array_push($where, ['sale_id' => $admin_id]);
        }
        $orders = Order::with('user')->where($where)
            ->whereDate('pay_time', $pay_at)->get();

        $admin = Admin::find($admin_id);

        $menu = $this->menu;
        $active = $this->active;

        return view('admin.sale.pre_order', compact('orders', 'admin', 'menu', 'active'));
    }
}
