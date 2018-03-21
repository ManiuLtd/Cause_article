<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14 0014
 * Time: 下午 3:25
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['_token'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function subUser()
    {
        return $this->belongsTo(User::class, 'sub_uid');
    }
}