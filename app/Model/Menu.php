<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31 0031
 * Time: 下午 3:34
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'admin_menu';

    protected $fillable = ['pid','title','icon','url','sort','display'];
}