<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21 0021
 * Time: 下午 3:22
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $guarded = ['_token','_method'];

    public $cache_key = 'brand_list';

    protected $cache_time = 60;

    public function cacheList()
    {
        return \Cache::remember($this->cache_key, $this->cache_time, function () {
            return $this->select('id', 'name as title', 'domain as pinyin')->where('type', 0)->get();
        });
    }
}