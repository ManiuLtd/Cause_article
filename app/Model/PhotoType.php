<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 上午 9:58
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class PhotoType extends Model
{
    protected $fillable = ['name', 'sort'];

    //关联美图
    public function photo()
    {
        return $this->hasMany(Photo::class, 'type_id');
    }
}