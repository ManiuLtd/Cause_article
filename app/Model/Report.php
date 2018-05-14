<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23 0023
 * Time: 上午 11:19
 */

namespace App\Model;


use App\Model\Traits\ReportHelper;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use ReportHelper;

    public $timestamps = false;

    protected $guarded = ['_token'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'aid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

}