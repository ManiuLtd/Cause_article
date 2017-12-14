<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5 0005
 * Time: 下午 5:29
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    public $timestamps = false;
}