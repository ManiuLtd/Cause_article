<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11 0011
 * Time: 下午 4:21
 */

namespace App\Observers;


use App\Model\ArticleType;

class ArticleTypeObserver
{
    public function saved(ArticleType $type)
    {
        \Cache::forget($type->cache_key);
    }
}