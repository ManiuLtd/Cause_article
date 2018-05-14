<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/12 0012
 * Time: 下午 2:43
 */

namespace App\Observers;


use App\Model\Brand;

class BrandObserver
{
    public function saved( Brand $brand )
    {
        \Cache::forget($brand->cache_key);
    }
}