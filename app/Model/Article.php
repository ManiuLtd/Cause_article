<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ['_token','_method','editorValue'];

//    public $timestamps = false;

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
