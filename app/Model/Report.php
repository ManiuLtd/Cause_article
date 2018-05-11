<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23 0023
 * Time: 上午 11:19
 */

namespace App\Model;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    protected $guarded = ['_token'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'aid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    //通用报表数据
    public function report( $extension, $gid, $date, $for_length, $type, $admin_type = 'extension' )
    {
        foreach ($extension as $key => $value) {
            $tot_ext_count = 0;
            $tot_ext_price = 0;
            $tot_count = 0;
            $tot_price = 0;
            $tomorrow1 = Carbon::parse($date)->addDay()->startOfDay();
            $tomorrow2 = Carbon::parse($date)->startOfDay();

            for ($i = 1; $i <= $for_length; $i++) {
                //用户行
                if($admin_type == 'extension') {
                    $ext_count = app(Report::class)->orderCountExtension($type, $tomorrow2, $tomorrow1, $value->id);
                    $count[$tomorrow2->month."-".$tomorrow2->day] = $ext_count;
                    $tot_ext_price += $ext_count;
                } elseif($admin_type == 'sale') {
                    $ext_count = app(Report::class)->orderCountSale($type, $tomorrow2, $tomorrow1, $value->id);
                    list($order_count, $price) = explode('|', $ext_count);
                    $route = route('sale.per_order', [$tomorrow2->toDateString(), $value->id]);
                    $count[$tomorrow2->month."-".$tomorrow2->day] = "<a href='{$route}'>$order_count/$price</a>";
                    $tot_ext_price += $price;
                    $tot_ext_count += $order_count;
                }

                if($gid) {
                    //总计行（最后一次循环的时候才操作）
                    if ( count($extension) == $key + 1 ) {
                        if($admin_type == 'extension') {
                            $totcount = app(Report::class)->orderCountExtension($type, $tomorrow2, $tomorrow1);
                            $acount[ $tomorrow2->month . "-" . $tomorrow2->day ] = $totcount;
                            $tot_count += $totcount;
                        } elseif($admin_type == 'sale') {
                            $totcount = app(Report::class)->orderCountSale($type, $tomorrow2, $tomorrow1);
                            list($order_count, $price) = explode('|', $totcount);
                            $acount[ $tomorrow2->month . "-" . $tomorrow2->day ] = $order_count.'/'.$price;
                            $tot_count += $order_count;
                            $tot_price += $price;
                        }
                    }
                }

                $tomorrow1->subDay();
                $tomorrow2->subDay();
            }
            if($admin_type == 'extension') {
                $extension[ $key ][ 'count' ] = array_merge($count, [ '总计' => $tot_ext_price ]);
            } else {
                $extension[ $key ][ 'count' ] = array_merge($count, [ '总计' => $tot_ext_count."/".$tot_ext_price ]);
            }

            if($gid == 1) {
                //最后一次循环的时候才操作
                if ( count($extension) == $key + 1 ) {
                    if($admin_type == 'extension') {
                        $extension[ $key ][ 'tot_count' ] = array_merge($acount, [ '总计' => $tot_price ]);
                    } else {
                        $extension[ $key ][ 'tot_count' ] = array_merge($acount, [ '总计' => $tot_count.'/'.$tot_price ]);
                    }
                }
            }
        }

        return $extension;
    }

    //推广订单计算
    public function orderCountExtension( $admin_type, $last_day, $tot_tomorrow, $admin_id = 0 )
    {
        $where = [['state', 1],['refund_state', 0]];
        if($admin_id) {
            array_push($where, ['admin_id', $admin_id]);
        }
        array_push($where, ['admin_type', $admin_type]);
        $orders = Order::where($where)->whereBetween('pay_time', [ $last_day, $tot_tomorrow ])->get();
        $price = 0;
        foreach ($orders as $order) {
            $price += floor($order->price * 0.05);
        }
        return $price;
    }

    //售前服务订单计算
    public function orderCountSale( $admin_type, $last_day, $tot_tomorrow, $admin_id = 0 )
    {
        $where = [['state', 1],['refund_state', 0]];
        if($admin_id) {
            array_push($where, ['sale_id', $admin_id]);
        }
        array_push($where, ['sale_type', $admin_type]);

        $orders = Order::where($where)->whereBetween('pay_time', [ $last_day, $tot_tomorrow ])->get();
        $price = 0;
        foreach ($orders as $order) {
            $price += $order->price;
        }

        return $orders->count()."|".$price;
    }
}