<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/22 0022
 * Time: 下午 5:32
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Integral extends Model
{
    protected $table = 'integral';

    public $timestamps = false;

    public function commission($id)
    {
        return Integral::where('user_id', $id)->sum('price');
    }
}