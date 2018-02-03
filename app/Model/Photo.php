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
    protected $fillable = ['type_id', 'url', 'name', 'brand_id'];


//    public function setUrlAttribute( $value )
//    {
//        $this->attributes['url'] = "/uploads/{$value}";
//    }

    public function type()
    {
        return $this->belongsTo(PhotoType::class, 'type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}