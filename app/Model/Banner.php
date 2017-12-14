<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/17 0017
 * Time: 下午 4:52
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = ['_token','method'];
}