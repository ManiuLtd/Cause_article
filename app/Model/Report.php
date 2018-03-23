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
    public function report( $extension, $gid, $tomorrow, $tot_tomorrow, $for_length, $type )
    {
        foreach ($extension as $key => $value) {
            $tot_ext_count = 0;
            $tot_count = 0;
            for ($i = 1; $i <= $for_length; $i++) {
                //用户行
                $last_day = Carbon::parse('tomorrow')->subDays($i);
                $ext_count = app(Report::class)->orderCount($type, $last_day, $tomorrow, $value->id);
                $count[$last_day->month."-".$last_day->day] = $ext_count;
                $tot_ext_count += $ext_count;

                if($gid) {
                    //总计行（最后一次循环的时候才操作）
                    if ( count($extension) == $key + 1 ) {
                        $totcount = app(Report::class)->orderCount($type, $last_day, $tot_tomorrow);
                        $acount[ $last_day->month . "-" . $last_day->day ] = $totcount;
                        $tot_count += $totcount;
                        $tot_tomorrow = $last_day;
                    }
                }

                $tomorrow = $last_day;
            }
            $tomorrow = Carbon::parse('tomorrow');
            $extension[$key]['count'] = array_merge($count, ['总计'=>$tot_ext_count]);
            if($gid == 1) {
                //最后一次循环的时候才操作
                if ( count($extension) == $key + 1 ) {
                    $extension[ $key ][ 'tot_count' ] = array_merge($acount, [ '总计' => $tot_count ]);
                }
            }
        }
//        die;
        return $extension;
    }

    //计算
    public function orderCount( $admin_type, $last_day, $tot_tomorrow, $admin_id = 0 )
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
}