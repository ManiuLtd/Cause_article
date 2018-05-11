<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30 0030
 * Time: 上午 11:17
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'sort'];

    protected $lists_cache_time = 30;

    public function lists()
    {
        \Cache::remember('article_type', $this->lists_cache_time, function () {

            return $this->orderBy('sort', 'asc')->get();
        });
    }
}