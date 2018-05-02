<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12 0012
 * Time: 上午 9:24
 */

namespace App\Http\Controllers\Index;

use App\Jobs\templateMessage;
use App\Model\Article;
use App\Model\Brand;
use App\Model\FamilyMessage;
use App\Model\Footprint;
use App\Model\Message;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use ClassesWithParents\F;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserArticleController extends CommonController
{
    //我的头条列表
    public function index(Request $request, $uid = 0)
    {
        if($uid) {
            $list = UserArticles::with('article')->where('uid', $uid)->orderBy('created_at', 'desc')->paginate(7);

            $user = User::find($uid);
        } else {
            $list = UserArticles::with('article')->where('uid', session('user_id'))->orderBy('created_at', 'desc')->paginate(7);

            $user = User::find(session('user_id'));
        }

        //微信分享配置
        $app = new Application(config('wechat'));
        $js = $app->js;

        if($request->ajax()) {
            $view = view('index.template.__user_article', compact('list'))->render();

            return response()->json(['html' => $view]);
        }

        return view('index.user_article', compact('list', 'user', 'js'));
    }

    /**
     * 我的文章详细页
     * @param UserArticles $articles 用户文章id
     * @param int $ex_id 分享人id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail( Request $request, UserArticles $articles, $ex_id = 0 )
    {
        $uid = session('user_id');
        $user = User::find($uid);

        $addfootid = '';
        $fkarticle = '';
        $uarticle = $articles->with('user.extension', 'article')->where('id', $articles->id)->first();

        //创建关联关系并关联后台员工id和类型
        if(!$user->extension_id && $user->subscribe == 1 && $articles->uid != $uid && $user->type == 1 && $uarticle->user->extension_id != $uid) {
            $user->admin_id = $uarticle->user->admin_id;
            $user->admin_type = $uarticle->user->admin_type;
            $user->extension_id = $articles->uid;
            $user->extension_up = optional($uarticle->user->extension)->extension_id;
            $user->extension_at = date('Y-m-d H:i:s', time());
            $user->ex_type = 1;
            $user->save();

            //推送【推荐会员成功提醒】模板消息
//            $app = new Application(config('wechat'));
            $msg = [
                "first"     => "恭喜您，有新的会员加入您的事业爆文团队！",
                "keyword1"  => $user->wc_nickname,
                "keyword2"  => date('Y-m-d H:i:s',time()),
                "keyword3"  => '扫描个人专属二维码',
                "remark"    => "您的队伍越来越强大了哦，请再接再厉！"
            ];
            dispatch(new templateMessage($uarticle->user->openid, $msg, config('wechat.template_id.extension_user'), config('app.url')));
//            template_message($app, $uarticle->user->openid, $msg, config('wechat.template_id.extension_user'), config('app.url'));
        }

        //获取品牌
        $brand = Brand::find(optional($uarticle->user)->brand_id);

        if ( $uarticle->uid != $uid ) {
            //用户文章第一次阅读则推送文本消息给该文章拥有者
            $openid = User::where('id', $uid)->value('openid');
            $cache_name = $articles->id . $openid;
            if ( !Cache::has($cache_name) ) {
                //推送消息
                $context = "有人对你的头条感兴趣！还不赶紧看看是谁~\n\n头条标题：《{$uarticle->article[ 'title' ]}》\n\n<a href='http://bw.eyooh.com/visitor_record'>【点击这里】查看谁对我的头条感兴趣>></a>";
                message($uarticle->user[ 'openid' ], 'text', $context);
                Cache::put($cache_name, 1, 60);
            }
            //公共文章浏览数+1
            Article::where('id', $uarticle->aid)->increment('read', 1);
            //用户文章浏览数+1
            $articles->increment('read', 1);

            //更新第一次阅读状态
            if ( $uarticle->first_read == 0 ) {
                $articles->update([ 'first_read' => 1 ]);
            }
            //记录访客足迹(停留时间处理)
            $foot = [
                'uid' => $uarticle->uid,
                'see_uid' => $uid,
                'uaid' => $articles->id,
                'ex_id' => $ex_id,
                'type' => 1,
                'from' => $request->from
            ];
            $add = Footprint::Create($foot);
            $addfootid = $add->id;

            //判断访客是否已拥有该文章
            $fkarticle = UserArticles::where([ 'aid' => $uarticle->article[ 'id' ], 'uid' => $uid ])->first();
        }

        $uarticle[ 'brand' ] = Brand::find($uarticle->article[ 'brand_id' ]);

        //微信分享配置
        $app = new Application(config('wechat'));
        $js = $app->js;

        //判断是否是会员或会员已过期
        $member_time = Carbon::parse($uarticle->user[ 'membership_time' ])->gt(Carbon::parse('now'));

        return view('index.user_article_details', [ 'user' => $user, 'res' => $uarticle, 'brand' => $brand, 'footid' => $addfootid, 'js' => $js, 'member_time' => $member_time, 'fkarticle' => $fkarticle ]);
    }

    /**
     * 我的文章详情页上传个人二维码
     */
    public function uploadQrcode( Request $request )
    {
        $path = base64Toimg($request->url, 'user_qrcode');
        $save = User::where('id', \Session::get('user_id'))->update([ 'qrcode' => "/uploads/" . $path[ 'path' ] ]);
        if ( $save ) {
            return response()->json([ 'state' => 0, 'errormsg' => '上传二维码成功' ]);
        } else {
            return response()->json([ 'state' => 401, 'errormsg' => '上传二维码失败' ]);
        }
    }

    /**
     * @title 文章被其他用户分享时分享数+1
     * @param $articles UserArticles
     * @param $ex_id
     */
    public function userArticleShare( UserArticles $articles, $ex_id )
    {
        if ( $articles->uid != session()->get('user_id') ) {
            if ( $articles->isrs == 0 ) $articles->update([ 'isrs' => 1 ]);
            //用户文章分享数+1
            $articles->increment('share', 1);
            $data = [
                'uid'     => $articles->uid,
                'see_uid' => session()->get('user_id'),
                'uaid'    => $articles->id,
                'ex_id'   => $ex_id,
                'type'    => 2
            ];
            Footprint::create($data);
            //公共文章分享数+1
            Article::where('id', $articles->aid)->increment('share', 1);
        }
    }

    /**
     * @title 用户在用户文章详细页停留的时间
     * @param Request $request
     */
    public function userArticleTime( Request $request )
    {
        Footprint::where('id', $request->id)->update([ 'residence_time' => $request->time ]);
    }

    /**
     * @title  分享和阅读列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readShare(Request $request, $type)
    {
        $lists = Footprint::with('userArticle.article', 'user')->where([ 'uid' => session('user_id'), 'type' =>$type ])->orderBy('id', 'desc')->paginate(5);

        if($request->ajax()) {
            $view = view('index.template.__read_share', compact('lists'))->render();

            return response()->json(['html' => $view]);
        }

        return view('index.read_share', compact('lists'));
    }

    /**
     * @title  用户文章访客记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visitorRecord(Request $request)
    {
        $uid = session()->get('user_id');
        //判断用户会员是否过期
        $user = User::where('id', $uid)->first();
        $list = UserArticles::with('article', 'footprint')->where([ 'uid' => $uid, 'first_read' => 1 ])->orderBy('updated_at', 'desc')->paginate(5);
        $list->transform(function ($value) {
            //unique 方法返回集合中所有唯一的项目
            $user_list = $value->footprint->unique('see_uid');
            $new = collect($value);
            $new->put('user_count', count($user_list));
            $new->put('user',collect($user_list)->transform(function ($user) {
                return collect($user)->put('user_list', User::where('id', $user[ 'see_uid' ])->select('head')->first());
            }));

            return $new;
        });

        //准客户数量
        $prospect = Footprint::where('uid', $uid)->get()->unique('see_uid');

        //个人文章今日浏览数
        $today_see = Footprint::where('uid', $uid)->whereDate('created_at', date('Y-m-d', time()))->count();

        if($request->ajax()) {
            $view = view('index.template.__visitor_record',compact('list', 'user'))->render();

            return response()->json(['html' => $view]);
        }

        return view('index.visitor_record', compact('list', 'today_see', 'prospect', 'user'));
    }

    /**
     * @title 用户文章访客详细列表
     * @param $id '用户文章表id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visitorDetails( $id )
    {
        $visitor_article = UserArticles::with([ 'article' => function ( $query ) {
            $query->select('id', 'title', 'pic');
        }, 'user' ])->where('id', $id)->first();

        $footprint = Footprint::where('uaid', $id)->orderBy('id', 'desc')->paginate(6);
        foreach ( $footprint as $key => $value ) {
            //用户分享层级关系
            if($value->ex_id && $value->ex_id != $value->uid && $value->type == 1) {
                $footprint[$key]['extension'] = app(Footprint::class)->extension_user($value);
            }

            Footprint::where('id', $value['id'])->update(['new' => 0]);
            $footprint[$key]['user'] = User::where('id', $value['see_uid'])->select('head', 'wc_nickname')->first();
        }

        $res = $visitor_article;

        if(\request()->ajax()){
            $html = view('index.template.__visitor', compact('res', 'footprint'))->render();
            return response()->json(['html' => $html]);
        }

        return view('index.visitor_detail', compact('res', 'footprint'));
    }

    /**
     * @title 在线询问用户文章
     * @param $id '用户文章表id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chatroom( User $user, $aid )
    {
//        $res = UserArticles::with('user')->where('id', $id)->first();
        session(['chat_aid' => $aid]);

        return view('index.chatroom', compact('user'));
    }

    /**
     * @title  提交在线咨询信息
     * @param $request Request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submitMessage( Request $request )
    {
        $data = $request->except('_token');
        $data[ 'sub_uid' ] = session()->get('user_id');
        $data[ 'order_id' ] = date('YmdHis') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $data[ 'created_at' ] = date('Y-m-d H:i:s');
        $add = Message::create($data);
        if ( $add->id ) {
            //推送【咨询消息】模板消息
            if ( $request->type == 1 ) {
                $type = '健康问题';
            } elseif ( $request->type == 2 ) {
                $type = '加盟事业';
            } else {
                $type = '其他';
            }
            $user = User::where('id', $request->uid)->select('openid', 'membership_time', 'subscribe')->first();
            if($user->subscribe) {
                if ( Carbon::parse($user->membership_time)->gt(Carbon::parse('now')) ) {
                    $msg = [
                        "first"    => $request->name . "有一个【" . $type . "】的需求向您咨询，快打开看看吧~。",
                        "keyword1" => $request->name,
                        "keyword2" => $request->phone,
                        "keyword3" => $data[ 'order_id' ],
                        "remark"   => "点击查看详情。"
                    ];
                } else {
                    $msg = [
                        "first"    => mb_substr($request->name, 0, 1, 'utf-8') . "**有一个 $type 的需求向您咨询，快打开看看吧~。",
                        "keyword1" => mb_substr($request->name, 0, 1, 'utf-8') . "**",
                        "keyword2" => substr($request->phone, 0, 3) . "********",
                        "keyword3" => $data[ 'order_id' ],
                        "remark"   => "点击查看详情。"
                    ];
                }
                dispatch(new templateMessage($user->openid, $msg, config('wechat.template_id.consult_message'), route('message_detail', [ 'id' => $add->id ])));
//                $app = new Application(config('wechat'));
//                template_message($app, $user->openid, $msg, config('wechat.template_id.consult_message'), route('message_detail', [ 'id' => $add_id ]));
            }
            $aid = session('chat_aid');
            session(['chat_aid' => '']);

            return redirect()->route('user_article_details', $aid);
        }
    }

    /**
     * 咨询列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function messageList(Request $request, $type)
    {
        switch ($type) {
            case 1:
                $lists = Message::with('subUser')->where('uid', session('user_id'))->orderBy('id', 'desc')->paginate(5);
                break;
            case 2:
                $lists = FamilyMessage::with('subUser')->where('user_id', session('user_id'))->orderBy('id', 'desc')->paginate(5);
                break;
        }
        $membership = User::where('id', session('user_id'))->value('membership_time');
        $time = Carbon::parse($membership)->gt(Carbon::now());

        if($request->ajax()) {
            $view = view('index.template.__message', compact('lists', 'time', 'type'))->render();

            return response()->json(['html' => $view]);
        }

        return view('index.message', compact('lists', 'time'));
    }

    /**
     * @title 普通咨询详细内容
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageDetail( $id )
    {
        $message = Message::with('user', 'subUser')->where('id', $id)->first();

        $membership_time = Carbon::parse('now')->gt(Carbon::parse($message->user['membership_time']));

        return view('index.message_detail', compact('message', 'membership_time'));
    }

    public function familyMessageDtail( FamilyMessage $message )
    {
        return view('index.message_family_detail', compact('message'));
    }

    /**
     * @title 访客记录统计页
     * @param $aid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function connection( $aid )
    {
        $foot = Footprint::with('user')->where('id', $aid)->first();

        $foot_list = Footprint::with('userArticle.article')->where(['see_uid'=>$foot->user->id, 'uid'=>session('user_id')])->orderBy('created_at', 'desc')->get();

        return view('index.visitor_record_see', compact('foot', 'foot_list'));
    }

    /**
     * 准客户
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function prospect(Request $request)
    {
        //准客户数量
        $lists = Footprint::with('userArticle.article', 'user')->where('uid', session('user_id'))->orderBy('created_at', 'desc')->paginate(15);
//        dump($lists);die;
        $prospect = $lists->unique('see_uid');
        if($request->ajax()) {
            $view = view('index.template.__visitor_prospect', compact('prospect'))->render();

            return response()->json(['html' => $view]);
        }

        return view('index.visitor_prospect', compact('lists', 'prospect'));
    }

    /**
     * @title 访客记录统计页
     * @param $uid  '查找的用户id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contacts( $uid )
    {
        //阅读
        $read = Footprint::where([ 'uid' => session('user_id'), 'see_uid' => $uid, 'type' => 1 ])->count();
        //分享
        $share = Footprint::where([ 'uid' => session('user_id'), 'see_uid' => $uid, 'type' => 2 ])->count();
        //最近访问的时间
        $foot = Footprint::with([ 'user' => function ( $query ) {
            $query->select('id', 'head', 'wc_nickname');
        } ])->where([ 'uid' => session('user_id'), 'see_uid' => $uid ])->orderBy('id', 'desc')->first();

        return view('index.connection', compact('read', 'share', 'foot'));
    }

    /**
     * 提醒用户有人对他的文章感兴趣，但未上传自己的二维码
     * @param $user
     */
    public function tipUserQrcode(User $user)
    {
        Cache::remember('tip_user'.$user->openid, 5 * 60, function () use($user){
            $msg = [
                "first"    => '未能成功拨打电话或添加微信',
                "keyword1" => '爆文访客',
                "keyword2" => date('Y-m-d H:i', time()),
                "remark"   => '因您未上传微信二维码，访客无法添加您的微信。请尽快上传二维码防止错失顾客线索。'
            ];
            dispatch(new templateMessage($user->openid, $msg, config('wechat.template_id.tip_upload_qrcode'), route('visitor_record')));
//            $app = new Application(config('wechat'));
//            template_message($app, $user->openid, $msg, config('wechat.template_id.tip_upload_qrcode'), route('visitor_record'));

            return true;
        });
    }
}