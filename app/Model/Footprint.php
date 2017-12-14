<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/10 0010
 * Time: 下午 4:24
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Footprint extends Model
{
    protected $guarded = ['_token','_method'];

    public function userArticle()
    {
        return $this->belongsTo(UserArticles::class, 'uaid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'see_uid');
    }
}