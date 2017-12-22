<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22 0022
 * Time: 下午 4:43
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class AdminDistribution extends Model
{
    protected $table = 'admin_distribution';

    protected $fillable = ['order_id', 'admin_id', 'type', 'created_at'];

    public $timestamps = false;
}