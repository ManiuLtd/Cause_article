<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/10 0010
 * Time: 下午 3:35
 */
return [
    'wechat_config' => [
        'debug'  => true,
        'app_id' => env('WECHAT_APP_ID'),
        'secret' => env('WECHAT_APP_SECRET'),
        'token'  => 'baowen',
        'aes_key' => 'AA2UIzvvI1KDPbD3eMPXrUpPvBMRkySt9YJSWaab3cd', // 可选
        'log' => [
            'level' => 'debug',
            'file'  => storage_path('logs/easywechat.log'), // XXX: 绝对路径！！！！
        ],
        /********微信支付*******/
        'payment' => [
            'merchant_id'        => '1483507812',
            'key'                => 'szkczx2017baowennewoab7102xzckzs',
            'cert_path'          => app_path('cert/apiclient_cert.pem'), // XXX: 绝对路径！！！！
            'key_path'           => app_path('cert/apiclient_key.pem'),  // XXX: 绝对路径！！！！
            'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
        ]
    ]
];