<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/10 0010
 * Time: 下午 12:27
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Text;

class WechatController extends Controller
{
    public function index()
    {
        $options = config('wechat');
        $app = new Application($options);
        //菜单
        $this->button();

        $app->server->setMessageHandler(function ($message) use ($app) {
            switch ($message->MsgType) {
                //收到事件消息
                case 'event':
                    return $this->_event($app,$message->FromUserName,$message->Event,$message->EventKey);
                    break;
                //收到文字消息
                case 'text':
                    return "欢迎关注我!";
                    break;
            }
        });
        $response = $app->server->serve();
        // 将响应输出
        return $response;
    }

    //创建普通菜单
    public function button()
    {
        $options = config('wechat');
        $app = new Application($options);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "view",
                "name" => "发现爆文",
                "url"  => "http://bw.eyooh.com/"
            ],[
                "type" => "view",
                "name" => "个人中心",
                "url"  => "http://bw.eyooh.com/user"
            ],[
                "type" => "view",
                "name" => "谁查看我",
                "url"  => "http://bw.eyooh.com/visitor_record"
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
        switch ($event){
            //已关注公众号的
            case 'SCAN':
                if(!User::where('openid', $FromUserName)->value('subscribe')){
                    User::where('openid',$FromUserName)->update(['subscribe'=>1]);
                }
                if(is_numeric($eventkey)){
                    return $this->register($app,$FromUserName,$eventkey);
                }else{
                    $this->bcuserArticle($FromUserName,$eventkey);
                }
                break;
            //未关注公众号的
            case 'subscribe':
                //更改用户关注状态
                User::where('openid',$FromUserName)->update(['subscribe'=>1]);
                if($eventkey) {
                    //扫推送二维码关注公众号（创建账号）
                    if (strpos($eventkey, '_') !== false) {
                        $eventkey = str_replace('qrscene_', '', $eventkey);
                    }

                    if (is_numeric($eventkey)) {
                        $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
                        message($FromUserName, 'text', $context);
                        return $this->register($app, $FromUserName, $eventkey);
                    } else {
                        $this->bcuserArticle($FromUserName, $eventkey);
                    }
                } else {
                    $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
                    message($FromUserName, 'text', $context);
                }
                break;
            //取消关注公众号
            case 'unsubscribe':
                User::where('openid',$FromUserName)->update(['subscribe'=>0]);
                break;
        }
    }

    //创建账号或已有账号关联关系
    public function register($app,$FromUserName,$eventkey)
    {
        //查找该用户
        $fuser = User::where('openid',$FromUserName)->first();
        if($fuser && $fuser->id !==$eventkey){
            //用户已存在 -> 关联关系
            //会员时间过期或没有
            if(Carbon::parse('now')->gt(Carbon::parse($fuser->membership_time))){
                if($fuser->extension_id == 0 && $fuser->dealer_id == 0 && $fuser->admin_id == 0) {
                    //当用户本来没有推广用户和经销商的时候
                    $pinfo = User::find($eventkey);
                    if ($pinfo->type == 2) {
                        $extension = 0;$dealer = $pinfo->id;
                    } else {
                        $extension = $pinfo->id;$dealer = $pinfo->dealer_id;
                    }
                    User::where('openid',$FromUserName)->update(['extension_id'=>$extension,'dealer_id'=>$dealer]);

                    //推送【推荐会员成功提醒】模板消息
                    $msg = [
                        "first"     => "你好，【" . $fuser->wc_nickname . "】已通过扫描您的专属二维码被推荐成为会员。",
                        "keyword1"  => $fuser->wc_nickname,
                        "keyword2"  => date('Y-m-d H:i:s',time()),
                        "remark"    => "感谢您的推荐。"
                    ];
                    template_message($app, $pinfo->openid, $msg, config('wechat.template_id.extension_user'), config('app.url'));
                    //推广奖励操作
                    extension($eventkey);
                }
            }
        }else {
            //用户不存在 -> 创建账号并关联关系
            $userinfores = $app->user->get($FromUserName);
            $pinfo = User::find($eventkey);
            if ($pinfo->type == 2) {
                $extension = 0;$dealer = $pinfo->id;
            } else {
                $extension = $pinfo->id;$dealer = $pinfo->dealer_id;
            }
            $data = [
                'wc_nickname' => $userinfores['nickname'],
                'head' => $userinfores['headimgurl'],
                'openid' => $userinfores['openid'],
                'extension_id' => $extension,
                'dealer_id' => $dealer,
                'subscribe' => $userinfores['subscribe']
            ];
            //保存用户
            User::create($data);

            //推送【推荐会员成功提醒】模板消息
            $msg = [
                "first"     => "你好，【". $userinfores['nickname'] ."】已通过扫描您的专属二维码被推荐成为会员。",
                "keyword1"  => $userinfores['nickname'],
                "keyword2"  => date('Y-m-d H:i:s',time()),
                "remark"    => "感谢您的推荐。"
            ];
            template_message($app, $FromUserName, $msg, config('wechat.template_id.extension_user'), config('app.url'));
            //推广奖励操作
            extension($eventkey);
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
        list($uid, $aid) = explode('|', $eventkey);
        $data = [
            'uid' => $uid,
            'aid' => $aid
        ];
        UserArticles::create($data);
        //推送客服消息
        $context = "恭喜老师，先给你个么么哒\n\n您已踏上成功签单之路！\n\n事业头条可以帮您一秒在文章中嵌入专属名片，让每一次分享都成为获客商机，挖掘价值准客户。\n\n点击下方菜单栏【发现爆文】立刻在文章中嵌入我的名片\n\n↓ ↓ ↓ ↓ ↓";
        message($FromUserName, 'text', $context);
    }

}