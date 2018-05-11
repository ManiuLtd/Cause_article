<?php

use Illuminate\Routing\Router;

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
//微信配置
Route::any('bw_wechat', 'Index\WechatController@index')->name('wechat');
//微信支付回调地址
Route::any('out_trade_no','Index\PayController@outTradeNo');

//静默授权获取用户微信信息
Route::group(['namespace' => 'Index', 'middleware' => ['wechat.oauth']], function () {
    Route::get('get_openid', 'GetOpenidController@getOpenid');
});

//前台路由组
Route::group(['namespace' => 'Index', 'middleware' => ['wechat.oauth:snsapi_userinfo', 'userinfo']], function (Router $router) {
    $router->group(['prefix' => '/'], function (Router $router) {
        $router->any('test/{user?}', 'IndexController@test')->name('test');
    });

    //首页
    $router->get('/{type?}', 'IndexController@index')->name('index.index')->where(['type' => '[0-9]+']);

    //推荐文章链接
    $router->view('extension_article', 'index.extension_article')->name('extension_article');

    //提交推荐文章
    $router->post('extension_article_post', 'IndexController@extensionArticle')->name('extension_article_post');

    //品牌接口
    $router->get('brand_list', 'IndexController@brandList')->name('brand_list');

    //完善弹窗信息
    $router->post('perfect_information/{user}', 'IndexController@perfectInformation')->name('perfect_information');

    //搜索文章
    $router->get('article_search/{key?}', 'ArticleController@searchArticle')->name('article_search');

    //公共文章详情
    $router->get('article_details/{article}/{share?}', 'ArticleController@articleDetails')->name('article_details');

    //文章喜欢数+1
    $router->get('article_like/{id}/{type}', 'ArticleController@articleLike')->name('article_like');

    //分享公共文章成功
    $router->get('article_share/{article}', 'ArticleController@articleShare')->name('article_share');

    //我的文章
    $router->get('user_article/{uid?}', 'UserArticleController@index')->name('user_article');

    //用户文章详情
    $router->get('user_article_details/{articles}/{et_id?}', 'UserArticleController@articleDetail')->name('user_article_details');

    //我的文章详情页上传二维码
    $router->post('upload_qrcode', 'UserArticleController@uploadQrcode')->name('upload_qrcode');

    //提醒用户有人对他的文章感兴趣，但未上传自己的二维码
    $router->get('tip_user_qrcode/{user}', 'UserArticleController@tipUserQrcode')->name('tip_user_qrcode');

    //使公共文章成为我的文章
    $router->get('become_my_article/{article_id}/{pid?}', 'ArticleController@becomeMyArticle')->name('become_my_article');

    //别人分享我的文章
    $router->get('user_article_share/{articles}/{ex_id?}', 'UserArticleController@userArticleShare')->name('user_article_share');

    //用户中心
    $router->get('user/{type?}/{dealer?}', 'UserController@index')->name('index.user');

    //查看推广规则
    $router->get('extension_rule', 'ExtensionController@rules')->name('extension_rule');

    //获取用户的base64头像和base64二维码
    $router->get('head_qrcode_base64', 'UserController@headQrcodeBase64')->name('head_qrcode_base64');

    //查看用户基础信息
    $router->any('user_basic/{user?}', 'UserController@userBasic')->name('user_basic');

    //查看获取个人二维码帮助页面
    $router->get('qrcode_help', 'UserController@qrcodeHelp')->name('qecode_help');

    //推广中心
    $router->get('extension', 'ExtensionController@index')->name('index.extension');

    //推广明细
    $router->get('extension_detail', 'ExtensionController@extensionDetail')->name('extension_detail');

    //明细列表
    $router->get('extension_list/{type}', 'ExtensionController@extensionList')->name('extension_list');

    //提现申请
    $router->get('cash', 'ExtensionController@applyCash')->name('index.apply_cash');

    //绑定提现账户
    $router->get('bind_account', 'ExtensionController@bindAccount')->name('index.bind_account');

    //获取验证码
    $router->post('get_code', 'ExtensionController@getCode')->name('index.get_code');

    //验证码验证
    $router->post('checkCode', 'ExtensionController@checkCode')->name('index.checkCode');

    //提现
    $router->post('get_money/{integralUse?}', 'ExtensionController@getMoney')->name('get_money');

    //提现记录
    $router->get('get_money_record/{integralUse?}', 'ExtensionController@getMoneyRecord')->name('get_money_record');

    //开通会员页面
    $router->get('open_member/{uid?}', 'UserController@openMember')->name('open_member');

    //获取用户在个人文章页面停留时间
    $router->post('user_article_time', 'UserArticleController@userArticleTime')->name('user_article_time');

    //在线咨询
    $router->get('chatroom/{user}/{aid?}','UserArticleController@chatroom')->name('chatroom');

    //提交在线咨询
    $router->post('submit_message','UserArticleController@submitMessage')->name('submit_message');

    //咨询列表
    $router->get('message_list/{type}', 'UserArticleController@messageList')->name('message_list');

    //咨询列表
    $router->get('message_detail/{id}', 'UserArticleController@messageDetail')->name('message_detail');

    //前端提交订单
    $router->post('submit_order','PayController@addOrder')->name('submit_order');

    //点击邀请好友
    $router->post('inviting','UserController@invitingFriends')->name('inviting');

    //用户文章访客记录
    $router->get('visitor_record', 'UserArticleController@visitorRecord')->name('visitor_record');

    //分享图片列表
    $router->get('extension_photo_list/{type?}', 'PhotoController@index')->name('extension_photo_list');

    //分享图详情
    $router->get('extension_poster/{photo}', 'PhotoController@poster')->name('extension_poster');

    //切换分享图
    $router->get('rand_photo/{count}/{type}', 'PhotoController@randPhoto')->name('rand_photo');

    //获取分享图片
    $router->post('get_share_photo', 'PhotoController@photoShare')->name('get_share_photo');

    //举报文章页面（选择举报类型）
    $router->get('report/{article_id}/{atype}', 'IndexController@report')->name('report');

    //举报文章内容页面（显示填写举报信息）
    $router->get('report_text/{article_id}/{atype}/{type}', 'IndexController@reportText')->name('report_text');

    //举报文章内容页面（显示填写举报信息）
    $router->post('report_post', 'IndexController@reportPost')->name('report_post');

    //家庭保障测评入口页面
    $router->get('family_appraisal/{uid}', 'IndexController@familyAppraisal')->name('family_appraisal');

    //家庭保障测评页面
    $router->match(['get', 'post'], 'family_appraisal_begin/{user?}', 'IndexController@familyAppraisalBegin')->name('family_appraisal_begin');

    //家庭保障测评结果
    $router->get('family_appraisal_result/{message}', 'IndexController@familyAppraisalResult')->name('family_appraisal_result');

    //查询提问家庭保障测试留言
    $router->get('family_message_detail/{message}', 'UserArticleController@familyMessageDtail')->name('family_message_detail');

    //未开通会员或会员时间到期中间件
    $router->group(['middleware' => 'membership'],function(){
        //用户文章被阅读和分享列表
        Route::get('read_share/{type}', 'UserArticleController@readShare')->name('read_share');
        //用户文章访客记录详情
        Route::get('visitor_details/{id}','UserArticleController@visitorDetails')->name('visitor_details');
        //找到访客
        Route::get('connection/{uid}','UserArticleController@contacts')->name('connection');
        //访客还看了
        Route::get('visitor_record_see/{aid}', 'UserArticleController@connection')->name('visitor_record_see');
        //准客户
        Route::get('visitor_prospect', 'UserArticleController@prospect')->name('visitor_prospect');
    });
});

//后台路由组
Route::group(['prefix'=>'admin', 'namespace' => 'Admin'], function () {

    //登录验证中间件
    Route::group(['middleware' => 'auth'],function(Router $router){
        //首页
        $router->get('index', 'IndexController@index')->name('admin');

        //权限管理资源路由
        $router->resource('menu', 'MenusController');

        //用户组管理资源路由
        $router->resource('admin_group', 'AdminGroupController');

        //后台用户管理资源路由
        $router->resource('admin_user', 'AdminUserController');

        //提交好的文章列表
        $router->get('good_article', 'ArticlesController@goodArticleList');

        //删除提交的好文章
        $router->post('delete_good_article/{article}', 'ArticlesController@deleteGoodArticle')->name('admin.delete_good_article');

        //文章管理资源路由
        $router->resource('articles', 'ArticlesController');

        //前台用户管理资源路由
        $router->get('user', 'UserController@index')->name('admin.user');

        //经销商列表
        $router->get('user_dealer', 'UserController@dealerList')->name('admin.dealerlist');

        //招商分配合作
        $router->post('admin_extension', 'UserController@adminExtension')->name('admin_extension');

        //成为经销商
        $router->get('be_dealer/{user}', 'UserController@be_dealer')->name('admin.be_dealer');

        //品牌管理资源路由
        $router->resource('brand', 'BrandController');

        //轮播图管理资源路由
        $router->resource('banner', 'BannerController');

        //文章分类资源路由
        $router->resource('article_type', 'ArticleTypeController');

        //美图类型资源路由
        $router->resource('photo_type', 'PhotoTypeController');

        //普通美图资源路由
        $router->resource('photo', 'PhotoController');

        //品牌美图资源路由
        $router->resource('photo_brand', 'PhotoBrandController');

        //显示和修改网站信息
        $router->match(['get','post'],'web_config','IndexController@webConfig')->name('web_config');

        //查看佣金
        $router->get('see_integral/{id}', 'UserController@seeIntegral')->name('see_integral');

        //设置佣金比例
        $router->post('set_integral/{user}', 'UserController@setIntegral')->name('set_integral_scale');

        //举报文章列表
        $router->get('report', 'ReportController@index')->name('admin.report');

        //总订单列表
        $router->get('order_list', 'OrderController@index');
//        $router->get('order_list1', 'OrderController@index1');
        //未支付订单列表
        $router->get('order_unpay', 'OrderController@unPay')->name('order.unpaylist');

        //已支付订单列表
        $router->get('order_pay', 'OrderController@Pay')->name('order.paylist');

        //退款列表
        $router->get('order_refund_list', 'OrderController@refundList');

        //分配订单
        $router->post('order_distribution', 'OrderController@distribution')->name('order.distribution');

        //订单备注
        $router->post('order_remark/{order}', 'OrderController@remark')->name('admin.order_remark');

        //订单退款
        $router->post('order_refund/{order}', 'OrderController@refund')->name('admin.refund');

        //删除订单
        $router->post('order_del/{order}', 'OrderController@delete')->name('admin.order_del');

        //订单报表
        $router->get('order_report', 'ReportController@report');

        //运营报表（显示运营所有员工的所有业绩）
        $router->get('operate_report', 'ReportController@operateReport')->name('admin.operate_report');

        //推广报表（显示招商所有员工的所有业绩）
        $router->get('extension_report', 'ReportController@extensionReport')->name('admin.extension_report');

        //提现列表
        $router->get('extract_cash', 'ExtractCashController@index')->name('admin.extract_cash');

        //客服备注提现列表
        $router->post('extract_remark/{integralUse}', 'ExtractCashController@remark')->name('admin.extract_remark');

        //客服完成提现
        $router->get('extract_complete/{integralUse}', 'ExtractCashController@complete')->name('admin.extract_complete');

        //客服修改会员时间
        $router->post('set_member_time', 'UserController@setMemberTime')->name('admin.set_member_time');

        //审核提交的好文章内容
        $router->post('examine_article/{article}', 'ArticlesController@examine')->name('examine_article');

        //销售员工路由组
        $router->group(['prefix'=>'sale'], function (Router $router) {
            //售前总列表
            $router->get('pre_index', 'ServerController@preSaleIndex')->name('sale.pre_index');

            //售前个人列表
            $router->get('pre', 'ServerController@preSale')->name('sale.pre');

            //服务标记
            $router->post('service/{sale}', 'ServerController@service')->name('sale.service');

            //分配售前用户
            $router->post('pre_distribution', 'ServerController@preDistribution')->name('sale.pre_distribution');

            //售前报表
            $router->get('pre_report', 'ReportController@preSale')->name('sale.pre_report');

            //业绩订单列表
            $router->get('pre_order/{pay_at}/{admin_id}', 'ServerController@preOrder')->name('sale.per_order');
        });
    });

    //上传图片
    Route::post('upload', 'IndexController@upload')->name('upload');
    //文本编辑器上传图片
    Route::post('ckeditor', 'IndexController@ckeditor')->name('ckeditor_image');

    Route::get('captcha/{tmp}', 'LoginController@captcha');

    Route::match(['get','post'], 'login', 'LoginController@login')->name('admin.login');
    
    Route::get('logout', 'LoginController@logout')->name('admin.logout');

});

Horizon::auth(function ($request) {
    if(optional(Auth::user())->id == 1) {
        return true; // false;
    }
    return false;
});


Auth::routes();

