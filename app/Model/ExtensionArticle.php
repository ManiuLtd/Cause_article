<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/8 0008
 * Time: 下午 6:13
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ExtensionArticle extends Model
{
    protected $table = 'ex_article';

    protected $fillable = ['user_id', 'url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}