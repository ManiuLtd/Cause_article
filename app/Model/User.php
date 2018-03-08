<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Foundation\Application;

class User extends Model
{
    protected $guarded = ['membership_time', 'extension_num', 'extension_type', '_token', '_method'];

    /**
     * @title  所属品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    /**
     * 用户订单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderList()
    {
        return $this->hasMany(Order::class, 'uid');
    }

    /**
     * 所属后台员工
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * @title  推广人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function extension()
    {
        return $this->belongsTo(User::class, 'extension_id');
    }

    /**
     * @title  经销商
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    /**
     * @title  我的文章
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function user_article()
    {
        return $this->hasMany(UserArticles::class, 'uid');
    }

    /**
     * @title  谁查看我的头条
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function user_foot()
    {
        return $this->hasMany(Footprint::class, 'uid');
    }

    public function user_account()
    {
        return $this->hasOne(UserAccount::class, 'user_id');
    }

    /**
     * 生成微信二维码
     * @param $uid
     * @return string
     */
    public function createQrcode($uid)
    {
        //创建永久二维码(已改为临时)
        $options = config('wechat');
        $app = new Application($options);
        $qrcode = $app->qrcode;
//        $result = $qrcode->forever($uid);
        $result = $qrcode->temporary($uid, 29 * 24 * 3600);
        $ticket = $result->ticket; // 或者 $result['ticket']
        return $qrcode->url($ticket);
    }
}
