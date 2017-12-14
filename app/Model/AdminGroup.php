<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31 0031
 * Time: 下午 3:49
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    protected $table = 'admin_group';

    protected $fillable = ['title','state','rule'];
}