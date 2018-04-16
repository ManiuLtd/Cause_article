<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4 0004
 * Time: 下午 10:10
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class FamilyMessage extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subUser()
    {
        return $this->belongsTo(User::class, 'sub_uid');
    }
}