<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $guarded = ['_token','_method','editorValue'];

//    public $timestamps = false;

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
