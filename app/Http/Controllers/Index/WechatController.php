<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/10 0010
 * Time: 下午 12:27
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitFunction\FunctionUser;
use App\Jobs\subscribe;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Material;
use EasyWeChat\Message\News;

class WechatController extends Controller
{
    use FunctionUser;

    protected $app;

    public function __construct( Application $app )
    {
        $this->app = $app;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function index()
    {
        $app = $this->app;

        $server = $app->server;

        $server->setMessageHandler(function ($message) use ($app) {
            switch ($message->MsgType) {
                //收到事件消息
                case 'event':
                    return $this->_event($app,$message->FromUserName,$message->Event,$message->EventKey);
                    break;
                //收到文字消息
                case 'text':
                    if($message->Content == '客服') {
                        return new News([
                            'title'       => '联系客服了解更多',
                            'description' => '有问题找客服哦~',
                            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000081&idx=1&sn=530d971319a07aa83ba18fb7798cb9f2&chksm=7b10a5144c672c02012588a1c29aadbcbd0a0cc71c8d632a0',
                            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj8icj9sBASDOABPA0ONMvrOVKudc2wYpRKd0tehrXG3I4hiaZSUIHlBtyKCwkqd4DmpNian82L1mNIQ/0?wx_fmt=jpeg',
                        ]);
                    } else {
                        return new Image(['media_id' => 'slQ7pC8xwK25Qm-fdcAWc04bcpd_t5KxNlhBqa2YdCs']);
                    }
                    break;
            }
        });

        //菜单
//        $this->button();

        $response = $server->serve();
        // 将响应输出
        return $response;
    }

    //创建普通菜单
    public function button()
    {
        $app = $this->app;
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "view",
                "name" => "发现爆文",
                "url"  => "http://bw.eyooh.com/"
            ],[
                "type" => "view",
                "name" => "谁查看我",
                "url"  => "http://bw.eyooh.com/visitor_record"
            ],[
                "name" => "服务·活动",
                "sub_button" => [
                    [
                        "type" => "click",
                        "name" => "联系客服",
                        "key"  => "Service_Click"
                    ],[
                        "type" => "view",
                        "name" => "个人中心",
                        "url"  => route('index.user')
                    ],[
                        "type" => "view",
                        "name" => "展业美图",
                        "url"  => route('extension_photo_list')
                    ],[
                        "type" => "view",
                        "name" => "邀请有礼",
                        "url"  => route('extension_rule')
                    ]
                ]
            ]
        ];
        $menu->add($buttons);
    }

    /**
     * @title 公众号事件推送分流
     * @param $app
     * @param $FromUserName 用户openid
     * @param $event 事件类型
     * @param $eventkey 事件参数
     */
    public function _event($app,$FromUserName, $event, $eventkey)
    {
        $user = User::where('openid', $FromUserName)->first();
        switch ($event){
            //已关注公众号的
            case 'SCAN':
                if(!User::where('openid', $FromUserName)->value('subscribe')){
                    User::where('openid',$FromUserName)->update(['subscribe'=>1]);
                }
                if(is_numeric($eventkey)){
                    if(optional($user)->id == $eventkey) {
                        return '扫自己的推广二维码是没用的喔';
                    }
                    return $this->register($app,$FromUserName,$eventkey);
                }else{
                    $this->bcuserArticle($FromUserName,$eventkey);
                }
                break;
            //未关注公众号的
            case 'subscribe':
                //更改用户关注状态
                if($user) {
                    $user->subscribe = 1;
                    $user->subscribe_at = Carbon::now()->toDateTimeString();
                    $user->save();
                }

                if($eventkey) {
                    //扫推送二维码关注公众号（创建账号）
                    if (strpos($eventkey, '_') !== false) {
                        $eventkey = str_replace('qrscene_', '', $eventkey);
                    }

                    if (is_numeric($eventkey)) {
                        $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
                        message($FromUserName, 'text', $context);
                        if(optional($user)->id == $eventkey) {
                            return '扫自己的推广二维码是没用的喔';
                        }
                        return $this->register($app, $FromUserName, $eventkey);
                    } else {
                        $this->bcuserArticle($FromUserName, $eventkey);
                    }
                } else {
                    $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
                    message($FromUserName, 'text', $context);
                }

                //推延迟队列发送图文消息
                $this->subscribeQueueMessage($FromUserName);

                break;
            //取消关注公众号
            case 'unsubscribe':
                User::where('openid',$FromUserName)->update(['subscribe'=>0]);
                break;
            case 'CLICK':
                $media_id = 'slQ7pC8xwK25Qm-fdcAWc2ibH64ATrBmOqi5u67BKtg';
                return new Image(['media_id' => $media_id]);
                break;
        }
    }

    //创建账号或已有账号关联关系
    public function register($app,$FromUserName,$eventkey)
    {
        //查找该用户
        $fuser = User::where('openid',$FromUserName)->first();
        if($fuser && optional($fuser)->id !== $eventkey){
            //用户已存在 -> 关联关系
            //会员时间过期或没有
            if(Carbon::parse('now')->gt(Carbon::parse($fuser->membership_time))){
                $pinfo = User::find($eventkey);
                if($fuser->extension_id == 0 && $fuser->admin_id == 0 && $fuser->type == 1 && optional($pinfo)->extension_id != $fuser->id) {
                    //当用户本来没有推广用户和经销商的时候
                    $data = [
                        'extension_id' => optional($pinfo)->id,
                        'extension_up' => optional($pinfo)->extension_id,
                        'admin_id' => optional($pinfo)->admin_id,
                        'admin_type' => optional($pinfo)->admin_type,
                        'extension_at' => date('Y-m-d H:i:s'),
                        'ex_type' => 2,
                        'subscribe_at' => date('Y-m-d H:i:s')
                    ];
                    User::where('openid', $FromUserName)->update($data);

                    //推送【推荐会员成功提醒】模板消息
                    $msg = [
                        "first"     => "恭喜您，有新的会员加入您的事业爆文团队！",
                        "keyword1"  => $fuser->wc_nickname,
                        "keyword2"  => date('Y-m-d H:i:s',time()),
                        "keyword3"  => '扫描个人专属二维码',
                        "remark"    => "您的队伍越来越强大了哦，请再接再厉！"
                    ];
                    template_message($app, $pinfo->openid, $msg, config('wechat.template_id.extension_user'), config('app.url'));
                    //推广奖励操作
//                    $this->extension($eventkey);
                }
            }
        }else {
            //用户不存在 -> 创建账号并关联关系
            $userinfores = $app->user->get($FromUserName);
            $pinfo = User::find($eventkey);
            $data = [
                'wc_nickname' => $userinfores['nickname'],
                'head' => $userinfores['headimgurl'],
                'openid' => $userinfores['openid'],
                'extension_id' => optional($pinfo)->id,
                'extension_up' => optional($pinfo)->extension_id,
                'admin_id' => optional($pinfo)->admin_id,
                'admin_type' => optional($pinfo)->admin_type,
                'extension_at' => date('Y-m-d H:i:s'),
                'subscribe' => $userinfores['subscribe'],
                'ex_type' => 2,
                'subscribe_at' => date('Y-m-d H:i:s')
            ];
            //保存用户
            User::create($data);

            //推送【推荐会员成功提醒】模板消息
            $msg = [
                "first"     => "恭喜您，有新的会员加入您的事业爆文团队！",
                "keyword1"  => $userinfores['nickname'],
                "keyword2"  => date('Y-m-d H:i:s',time()),
                "keyword3"  => '扫描个人专属二维码',
                "remark"    => "您的队伍越来越强大了哦，请再接再厉！"
            ];
            template_message($app, $pinfo->openid, $msg, config('wechat.template_id.extension_user'), config('app.url'));

            //推延迟队列发送图文消息
            $this->subscribeQueueMessage($FromUserName);

            //推广奖励操作
//            $this->extension($eventkey);
        }
    }

    /**
     * @title 使文章成为用户的文章
     * @param $eventkey (用户id|文章id)
     */
    public function bcuserArticle($FromUserName, $eventkey)
    {
        if(strpos($eventkey,'_') !== false){
            //之前未关注公众号扫【成为我的文章】二维码
            $eventkey = str_replace('qrscene_', '', $eventkey);
        }
        list($user_id, $article_id, $pid) = explode('|', $eventkey);

        //关联账号关系
        $user = User::find($user_id);
        if($pid) {
            $puser = User::find($pid);
            if ( empty($user->dealer_id) && empty($user->extension_id) && $pid != $user_id && $user->type != 2 ) {
                if ( Carbon::parse('now')->gt(Carbon::parse($user->membership_time)) ) {

                    User::where('id', $user_id)->update([
                            'extension_id' => $pid,
                            'extension_up' => $puser->extension_id,
                            'admin_id'     => $puser->admin_id,
                            'admin_type'   => $puser->admin_type,
                            'extension_at' => date('Y-m-d H:i:s'),
                            'ex_type'      => 2,
                            'subscribe'    => 1,
                            'subscribe_at' => date('Y-m-d H:i:s')
                        ]
                    );

                    //推送【推荐会员成功提醒】模板消息
                    $msg = [
                        "first"    => "恭喜您，有新的会员加入您的事业爆文团队！",
                        "keyword1" => $user->wc_nickname,
                        "keyword2" => date('Y-m-d H:i:s', time()),
                        "keyword3" => '查看您的文章',
                        "remark"   => "您的队伍越来越强大了哦，请再接再厉！"
                    ];
                    $options = config('wechat');
                    $app = new Application($options);
                    template_message($app, $puser->openid, $msg, config('wechat.template_id.extension_user'), config('app.url'));
                }
            }
        }
        $data = [
            'uid' => $user_id,
            'aid' => $article_id,
        ];
        UserArticles::create($data);
        //推送客服消息
        $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
        message($FromUserName, 'text', $context);
    }

    public function subscribeQueueMessage($openid)
    {
        $message = new News([
            'title'       => '如何进入并分享事业爆文？',
            'description' => '跟着教程一起学~',
            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000085&idx=1&sn=c03fb4f45d089a451ec386810c939e56&chksm=7b10a5104c672c06cfa53cd7c36b03f90edf7832f812d8fe5',
            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj0TlIiarlef2R5SZN5FtIWb8V3VaWRAbxIwibOU33sj4vmWrWwqMnGj312wDHpJe9w1UQ9sGQ2iap4w/0?wx_fmt=jpeg',
        ]);
        dispatch(new subscribe($openid, $message))->delay(Carbon::now()->addHour());

        $message = new News([
            'title'       => '如何设置个人名片信息？',
            'description' => '跟着教程一起学~',
            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000087&idx=1&sn=a307c049ada32d68b54ac8bb24a99548&chksm=7b10a5124c672c04114f0a184f63f87a44c24f74bf32bd0f3',
            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj0TlIiarlef2R5SZN5FtIWb8V3VaWRAbxIwibOU33sj4vmWrWwqMnGj312wDHpJe9w1UQ9sGQ2iap4w/0?wx_fmt=jpeg',
        ]);
        dispatch(new subscribe($openid, $message))->delay(Carbon::now()->addHours(3));

        $message = new News([
            'title'       => '如何提交好文章链接？',
            'description' => '喜欢的文章，一步提交，带上您的联系方式！',
            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000089&idx=1&sn=b024367ffb07de00ebec701524df5f56&chksm=7b10a51c4c672c0a75efa1507e845fa9327cf89e0afe9b872',
            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj0TlIiarlef2R5SZN5FtIWb8V3VaWRAbxIwibOU33sj4vmWrWwqMnGj312wDHpJe9w1UQ9sGQ2iap4w/0?wx_fmt=jpeg',
        ]);
        dispatch(new subscribe($openid, $message))->delay(Carbon::now()->addHours(5));

        $message = new News([
            'title'       => '如何查看访客信息？',
            'description' => '一分钟教会您查看访客留言！',
            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000091&idx=1&sn=1c766ea7e7cc1420c989bca3c3fe087c&chksm=7b10a51e4c672c089ec2c79429ae8662845628927dc46e68e',
            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj0TlIiarlef2R5SZN5FtIWb8V3VaWRAbxIwibOU33sj4vmWrWwqMnGj312wDHpJe9w1UQ9sGQ2iap4w/0?wx_fmt=jpeg',
        ]);
        dispatch(new subscribe($openid, $message))->delay(Carbon::now()->addHours(7));

        $message = new News([
            'title'       => '如何查看在线留言？',
            'description' => '不漏掉任何一个人的留言！',
            'url'         => 'http://mp.weixin.qq.com/s?__biz=MzU0MzAxMjEzOA==&mid=100000093&idx=1&sn=840ece3557cd7f8f96d9d384bf20ce56&chksm=7b10a5184c672c0e52610f00fff4af98e47b86a929de39781',
            'image'       => 'http://mmbiz.qpic.cn/mmbiz_jpg/dVqibJbicyOmj0TlIiarlef2R5SZN5FtIWb8V3VaWRAbxIwibOU33sj4vmWrWwqMnGj312wDHpJe9w1UQ9sGQ2iap4w/0?wx_fmt=jpeg',
        ]);
        dispatch(new subscribe($openid, $message))->delay(Carbon::now()->addHours(9));
    }

}