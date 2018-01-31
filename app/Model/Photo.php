<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 上午 10:04
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['type_id', 'url', 'name'];

    public function type()
    {
        return $this->belongsTo(PhotoType::class, 'type_id');
    }
}