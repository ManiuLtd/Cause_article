<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30 0030
 * Time: 上午 10:42
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class IntegralUse extends Model
{
    protected $table = 'integral_used';

    protected $fillable = [ 'integral', 'remark', 'state' ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}