<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15 0015
 * Time: 上午 11:44
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['_token'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }
}