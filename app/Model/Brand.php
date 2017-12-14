<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21 0021
 * Time: 下午 3:22
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = ['_token','_method'];
}