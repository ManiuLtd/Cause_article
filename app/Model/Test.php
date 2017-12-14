<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21 0021
 * Time: 下午 10:31
 */

namespace App;

//laravel模型类的基础写法
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Test
 *
 * @mixin \Eloquent
 */
class Test extends Model
{
    use SoftDeletes;
    //指定表名
    protected $table = '表名';

    //指定id
    protected $primakey = '主键';

    protected $dates = ['deleted_at'];

    //指定允许批量赋值的字段
    protected $fillable = ['xxx','xxx','xx'];

    //不允许批量赋值的字段
    protected $guarded = ['xxx','xx','x'];

    //自动维护时间。新增字段：create_at  更新字段：update_at
    public $timestamps = true;

    //维护的时间为时间戳格式
    protected function getDateFormat(){
        return time();
    }

    //默认输出不转换的时间戳
    protected function asDateTime($val){
        return $val;
    }

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        //设置模型匿名全局作用域
        static::addGlobalScope('age', function(Builder $builder) {
            $builder->where('age', '>', 200);
        });
    }
}