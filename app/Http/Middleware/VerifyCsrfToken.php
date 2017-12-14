<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/upload',   //上传图片
        'bw_wechat', //微信验证
        'open_member',
        'out_trade_no',//支付回调
        'submit_order',
        'invitingFriends',
        'admin/ckeditor',
    ];
}
