<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11 0011
 * Time: 下午 5:24
 */

namespace App\Model\Traits;

use Carbon\Carbon;
use App\Model\Order;

trait ReportHelper
{
    protected $where = [
        ['state', 1],
        ['refund_state', 0]
    ];

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

            if($admin_type == 'extension') {
                for ( $i = 1; $i <= $for_length; $i++ ) {
                    //用户行
                    $ext_count = $this->orderCountExtension($type, $tomorrow2, $tomorrow1, $value->id);
                    $count[ $tomorrow2->month . "-" . $tomorrow2->day ] = $ext_count;
                    $tot_ext_price += $ext_count;

                    if ( $gid ) {
                        //总计行（最后一次循环的时候才操作）
                        if ( count($extension) == $key + 1 ) {
                            $totcount = $this->orderCountExtension($type, $tomorrow2, $tomorrow1);
                            $acount[ $tomorrow2->month . "-" . $tomorrow2->day ] = $totcount;
                            $tot_count += $totcount;
                        }
                    }

                    $tomorrow1->subDay();
                    $tomorrow2->subDay();
                }

                $extension[ $key ][ 'count' ] = array_merge($count, [ '总计' => $tot_ext_price ]);
            } elseif ( $admin_type == 'sale' ) {
                for ( $i = 1; $i <= $for_length; $i++ ) {
                    //用户行
                    $ext_count = $this->orderCountSale($type, $tomorrow2, $tomorrow1, $value->id);
                    list($order_count, $price) = explode('|', $ext_count);
                    $route = route('sale.per_order', [ $tomorrow2->toDateString(), $value->id ]);
                    $count[ $tomorrow2->month . "-" . $tomorrow2->day ] = "<a href='{$route}'>$order_count/$price</a>";
                    $tot_ext_price += $price;
                    $tot_ext_count += $order_count;

                    if ( $gid ) {
                        //总计行（最后一次循环的时候才操作）
                        if ( count($extension) == $key + 1 ) {
                            $totcount = $this->orderCountSale($type, $tomorrow2, $tomorrow1);
                            list($order_count, $price) = explode('|', $totcount);
                            $acount[ $tomorrow2->month . "-" . $tomorrow2->day ] = $order_count . '/' . $price;
                            $tot_count += $order_count;
                            $tot_price += $price;
                        }
                    }

                    $tomorrow1->subDay();
                    $tomorrow2->subDay();
                }

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

    /**
     * 推广订单计算
     * @param $admin_type
     * @param $last_day
     * @param $tot_tomorrow
     * @param int $admin_id
     * @return float|int
     */
    public function orderCountExtension( $admin_type, $last_day, $tot_tomorrow, $admin_id = 0 )
    {
        $orders = $this->setOrderWhere($admin_id, $admin_type, 'admin', $last_day, $tot_tomorrow);
        $price = 0;
        foreach ($orders as $order) {
            $price += floor($order->price * 0.05);
        }
        return $price;
    }

    /**
     * 售前服务订单计算
     * @param $admin_type
     * @param $last_day
     * @param $tot_tomorrow
     * @param int $admin_id
     * @return string
     */
    public function orderCountSale( $admin_type, $last_day, $tot_tomorrow, $admin_id = 0 )
    {
        $orders = $this->setOrderWhere($admin_id, $admin_type, 'sale', $last_day, $tot_tomorrow);
        $price = 0;
        foreach ($orders as $order) {
            $price += $order->price;
        }

        return $orders->count()."|".$price;
    }

    /**
     * @param $admin_id
     * @param $admin_type
     * @param $key
     * @param $last_day
     * @param $tot_tomorrow
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function setOrderWhere( $admin_id, $admin_type, $key, $last_day, $tot_tomorrow )
    {
        $where = $this->where;
        if($admin_id) {
            array_push($where, ["{$key}_id", $admin_id]);
        }
        array_push($where, ["{$key}_type", $admin_type]);

        $orders = Order::where($where)->whereBetween('pay_time', [ $last_day, $tot_tomorrow ])->get();

        return $orders;
    }


}