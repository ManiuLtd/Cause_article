<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30 0030
 * Time: 下午 1:45
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $table = 'user_account';

    protected $fillable = ['user_id', 'type', 'name', 'card'];
}