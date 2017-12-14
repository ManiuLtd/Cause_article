<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = ['_token', '_method'];

    /**
     * @title  所属品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    /**
     * @title  推广人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function extension()
    {
        return $this->belongsTo(User::class,'extension_id');
    }

    /**
     * @title  经销商
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id');
    }

    /**
     * @title  我的文章
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_article()
    {
        return $this->hasMany(UserArticles::class,'uid');
    }
}
