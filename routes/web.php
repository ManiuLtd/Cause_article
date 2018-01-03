<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//前台路由组
Route::group(['namespace' => 'Index', 'middleware' => ['wechat.oauth:snsapi_userinfo', 'userinfo']], function () {
    //首页
    Route::get('/', 'IndexController@index')->name('index.index')->where(['type' => '[0-9]+']);
    //品牌接口
    Route::get('brand_list', 'IndexController@brandList')->name('brand_list');
    //完善弹窗信息
    Route::post('perfect_information/{user}', 'IndexController@perfectInformation')->name('perfect_information');
    //搜索文章1
    Route::get('article_search/{key?}', 'ArticleController@searchArticle')->name('article_search');
    //公共文章详情
    Route::get('article_details/{article}/{share?}', 'ArticleController@articleDetails')->name('article_details');
    //分享公共文章成功
    Route::get('article_share/{article}', 'ArticleController@articleShare')->name('article_share');
    //我的文章
    Route::get('user_article', 'UserArticleController@index')->name('user_article');
    //用户文章详情
    Route::get('user_article_details/{id}', 'UserArticleController@articleDetail')->name('user_article_details');
    //我的文章详情页上传二维码
    Route::post('upload_qrcode', 'UserArticleController@uploadQrcode')->name('upload_qrcode');
    //使公共文章成为我的文章
    Route::get('become_my_article/{user_id}/{article_id}/{pid?}', 'ArticleController@becomeMyArticle')->name('become_my_article');
    //别人分享我的文章
    Route::get('user_article_share/{id}', 'UserArticleController@userArticleShare')->name('user_article_share');
    //微信配置
    Route::any('bw_wechat', 'WechatController@index')->name('wechat');
    //用户中心
    Route::get('user/{type?}/{dealer?}', 'UserController@index')->name('index.user');
    //查看用户基础信息
    Route::any('user_basic/{user?}', 'UserController@userBasic')->name('user_basic');
    //查看获取个人二维码帮助页面
    Route::get('qrcode_help', 'UserController@qrcodeHelp')->name('qecode_help');
    //推广中心
    Route::get('extension', 'ExtensionController@index')->name('index.extension');
    //提现申请
    Route::get('cash', 'ExtensionController@applyCash')->name('index.apply_cash');
    //绑定提现账户
    Route::get('bind_account', 'ExtensionController@bindAccount')->name('index.bind_account');
    //获取验证码
    Route::post('get_code', 'ExtensionController@getCode')->name('index.get_code');
    //验证码验证
    Route::post('checkCode', 'ExtensionController@checkCode')->name('index.checkCode');
    //提现
    Route::post('get_money/{integralUse?}', 'ExtensionController@getMoney')->name('get_money');
    //提现记录
    Route::get('get_money_record/{integralUse?}', 'ExtensionController@getMoneyRecord')->name('get_money_record');
    //开通会员页面
    Route::get('open_member', 'UserController@openMember')->name('open_member');
    //获取用户在个人文章页面停留时间
    Route::post('user_article_time', 'UserArticleController@userArticleTime')->name('user_article_time');
    //在线咨询
    Route::get('chatroom/{id}','UserArticleController@chatroom')->name('chatroom');
    //提交在线咨询
    Route::post('submit_message','UserArticleController@submitMessage')->name('submit_message');
    //咨询列表
    Route::get('message_list', 'UserArticleController@messageList')->name('message_list');
    //咨询列表
    Route::get('message_detail/{id}', 'UserArticleController@messageDetail')->name('message_detail');
    //前端提交订单
    Route::post('submit_order','PayController@addOrder')->name('submit_order');
    //微信支付回调地址
    Route::any('out_trade_no','PayController@outTradeNo');
    //点击邀请好友
    Route::post('inviting','UserController@invitingFriends')->name('inviting');
    //用户文章访客记录
    Route::get('visitor_record', 'UserArticleController@visitorRecord')->name('visitor_record');
    //举报文章页面（选择举报类型）
    Route::get('report/{article_id}/{atype}', 'IndexController@report')->name('report');
    //举报文章内容页面（显示填写举报信息）
    Route::get('report_text/{article_id}/{atype}/{type}', 'IndexController@reportText')->name('report_text');
    //举报文章内容页面（显示填写举报信息）
    Route::post('report_post', 'IndexController@reportPost')->name('report_post');
    //未开通会员或会员时间到期中间件
    Route::group(['middleware' => 'membership'],function(){
        //用户文章被阅读和分享列表
        Route::get('read_share', 'UserArticleController@readShare')->name('read_share');
        //用户文章访客记录详情
        Route::get('visitor_details/{id}','UserArticleController@visitorDetails')->name('visitor_details');
        //找到访客
        Route::get('connection/{uid}','UserArticleController@connection')->name('connection');
    });
});

//后台路由组
Route::group(['prefix'=>'admin', 'namespace' => 'Admin'], function () {

    //登录验证中间件
    Route::group(['middleware' => 'auth'],function(){
        Route::get('index', 'IndexController@index')->name('admin');
        //权限管理资源路由
        Route::resource('menu', 'MenusController');
        //用户组管理资源路由
        Route::resource('admin_group', 'AdminGroupController');
        //后台用户管理资源路由
        Route::resource('admin_user', 'AdminUserController');
        //文章管理资源路由
        Route::resource('articles', 'ArticlesController');
        //前台用户管理资源路由
        Route::get('user', 'UserController@index')->name('admin.user');
        //经销商列表
        Route::get('user_dealer', 'UserController@dealerList')->name('admin.dealerlist');
        //成为经销商
        Route::get('be_dealer/{id}', 'UserController@be_dealer')->name('admin.be_dealer');
        //品牌管理资源路由
        Route::resource('brand', 'BrandController');
        //品牌管理资源路由
        Route::resource('banner', 'BannerController');
        //显示和修改网站信息
        Route::match(['get','post'],'web_config','IndexController@webConfig')->name('web_config');
        //查看佣金
        Route::get('see_integral/{id}', 'UserController@seeIntegral')->name('see_integral');
        //设置佣金比例
        Route::post('set_integral', 'UserController@setIntegral')->name('set_integral');
        //举报文章列表
        Route::get('report', 'ReportController@index')->name('admin.report');
        //总订单列表
        Route::get('order_list', 'OrderController@index');
        //未支付订单列表
        Route::get('order_unpay', 'OrderController@unPay')->name('order.unpaylist');
        //已支付订单列表
        Route::get('order_pay', 'OrderController@Pay')->name('order.paylist');
        //退款列表
        Route::get('order_refund_list', 'OrderController@refundList');
        //分配订单
        Route::post('order_distribution', 'OrderController@distribution')->name('order.distribution');
        //订单备注
        Route::post('order_remark/{order}', 'OrderController@remark')->name('admin.order_remark');
        //订单退款
        Route::post('order_refund/{order}', 'OrderController@refund')->name('admin.refund');
        //删除订单
        Route::post('order_del/{order}', 'OrderController@delete')->name('admin.order_del');
        //订单报表
        Route::get('order_report', 'ReportController@report');
        //运营报表（显示运营所有员工的所有业绩）
        Route::get('operate_report', 'ReportController@operateReport')->name('admin.operate_report');
        //推广报表（显示招商所有员工的所有业绩）
        Route::get('extension_report', 'ReportController@extensionReport')->name('admin.extension_report');
        //提现列表
        Route::get('extract_cash', 'ExtractCashController@index')->name('admin.extract_cash');
        //客服备注提现列表
        Route::post('extract_remark/{integralUse}', 'ExtractCashController@remark')->name('admin.extract_remark');
        //客服完成提现
        Route::get('extract_complete/{integralUse}', 'ExtractCashController@complete')->name('admin.extract_complete');
    });

    //上传图片
    Route::post('upload', 'IndexController@upload')->name('upload');
    //文本编辑器上传图片
    Route::post('ckeditor', 'IndexController@ckeditor')->name('ckeditor_image');

    Route::get('captcha/{tmp}', 'LoginController@captcha');

    Route::match(['get','post'], 'login', 'LoginController@login')->name('admin.login');
    
    Route::get('logout', 'LoginController@logout')->name('admin.logout');

});


Auth::routes();

