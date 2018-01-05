<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21 0021
 * Time: 下午 3:22
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $guarded = ['_token','_method'];
}