<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 下午 4:55
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserArticles extends Model
{
    protected $table = 'user_articles';

    protected $guarded = ['_token','_method'];

//    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class,'uid');
    }

    public function article(){
        return $this->belongsTo(Article::class,'aid');
    }

    public function footprint()
    {
        return $this->hasMany(Footprint::class, 'uaid');
    }
}