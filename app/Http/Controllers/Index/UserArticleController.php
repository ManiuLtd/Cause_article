<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12 0012
 * Time: 上午 9:24
 */

namespace App\Http\Controllers\Index;

use App\Model\Article;
use App\Model\Brand;
use App\Model\Footprint;
use App\Model\Message;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserArticleController extends CommonController
{
    //我的头条列表
    public function index($uid = 0)
    {
        if($uid) {
            $list = UserArticles::with('article')->where('uid', $uid)->orderBy('created_at', 'desc')->get();

            $user = User::find($uid);
        } else {
            $list = UserArticles::with('article')->where('uid', session('user_id'))->orderBy('created_at', 'desc')->get();

            $user = User::find(session('user_id'));
        }

        //微信分享配置
        $package = wecahtPackage();

        return view('index.user_article', compact('list', 'user', 'package'));
    }

    /**
     * 我的文章详细页
     * @param UserArticles $articles 用户文章id
     * @param int $ex_id 分享人id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail( UserArticles $articles, $ex_id = 0 )
    {
        $uid = session('user_id');
        $user = User::find($uid);
        $addfootid = '';
        $fkarticle = '';
        $uarticle = $articles->with('user', 'article')->where('id', $articles->id)->first();

        //获取品牌
        $brand = Brand::find($uarticle->user->brand_id);

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
                'created_at' => date('Y-m-d H:i:s', time()),
                'type' => 1
            ];
            $addfootid = Footprint::insertGetId($foot);

            //判断访客是否已拥有该文章
            $fkarticle = UserArticles::where([ 'aid' => $uarticle->article[ 'id' ], 'uid' => $uid ])->first();
        }

        $uarticle[ 'brand' ] = Brand::find($uarticle->article[ 'brand_id' ]);

        //微信分享配置
        $package = wecahtPackage();

        //判断是否是会员或会员已过期
        $member_time = Carbon::parse($uarticle->user[ 'membership_time' ])->gt(Carbon::parse('now'));

        return view('index.user_article_details', [ 'user' => $user, 'res' => $uarticle, 'brand' => $brand, 'footid' => $addfootid, 'package' => $package, 'member_time' => $member_time, 'fkarticle' => $fkarticle ]);
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
    public function readShare()
    {
        $list = Footprint::with('userArticle', 'user')->where([ 'uid' => session()->get('user_id') ])->orderBy('created_at', 'desc')->get();
        foreach ( $list->toArray() as $key => $value ) {
            $list[ $key ][ 'article' ] = Article::where('id', $value[ 'user_article' ][ 'aid' ])->first();
        }

        return view('index.read_share', compact('list'));
    }

    /**
     * @title  用户文章访客记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visitorRecord()
    {
        $uid = session()->get('user_id');
        //判断用户会员是否过期
        $member_time = User::where('id', $uid)->value('membership_time');

        $list = UserArticles::with('article', 'footprint')->where([ 'uid' => $uid, 'first_read' => 1 ])->orderBy('created_at', 'desc')->get()->toArray();
        foreach ( $list as $key => $value ) {
            //统计新访客(2018-2-2注释-》更改为显示多少个用户)
//            $list[ $key ][ 'new_count' ] = Footprint::where([ 'uaid' => $value[ 'id' ], 'new' => 1 ])->count();

            //去除重复用户获取单个用户id
            $user_list = remove_duplicate($value[ 'footprint' ]);
            $list[$key]['user_count'] = count($user_list);
            foreach ( $user_list as $k => $v ) {
                $list[ $key ][ 'user' ][ $k ] = User::where('id', $v[ 'see_uid' ])->select('head')->first();
            }
        }

        //准客户数量
        $prospect = remove_duplicate(Footprint::where('uid', $uid)->get());

        //个人文章今日浏览数
        $today_see = Footprint::where('uid', $uid)->whereDate('created_at', date('Y-m-d', time()))->count();

        return view('index.visitor_record', compact('list', 'today_see', 'prospect', 'member_time'));
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
        }, 'footprint'                                    => function ( $query ) {
            $query->orderBy('created_at', 'desc');
        }, 'user' ])->where('id', $id)->first();
        $footprint = $visitor_article->footprint;
        foreach ( $footprint as $key => $value ) {

            //用户分享层级关系
            if($value->ex_id) {
                $footprint[$key]['extension'] = app(Footprint::class)->extension_user($value);
            }

            Footprint::where('id', $value[ 'id' ])->update([ 'new' => 0 ]);
            $footprint[ $key ][ 'user' ] = User::where('id', $value[ 'see_uid' ])->select('head', 'wc_nickname')->first();
        }

        $res = $visitor_article;

        return view('index.visitor_detail', compact('res', 'footprint'));
    }

    /**
     * @title 在线询问用户文章
     * @param $id '用户文章表id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chatroom( User $user )
    {
//        $res = UserArticles::with('user')->where('id', $id)->first();

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
        $add_id = Message::insertGetId($data);
        if ( $add_id ) {
            //推送【咨询消息】模板消息
            if ( $request->type == 1 ) {
                $type = '健康问题';
            } elseif ( $request->type == 2 ) {
                $type = '加盟事业';
            } else {
                $type = '其他';
            }
            $openid = User::where('id', $request->uid)->select('openid', 'membership_time')->first();
            if ( Carbon::parse($openid->membership_time)->gt(Carbon::parse('now')) ) {
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
            $app = new Application(config('wechat'));
            template_message($app, $openid->openid, $msg, config('wechat.template_id.consult_message'), route('message_detail', [ 'id' => $add_id ]));

            return redirect()->back();
        }
    }

    /**
     * 咨询列表
     * @param $message Message
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageList(Message $message)
    {
        $list = $message->with('user')->where('uid', session('user_id'))->get();

        return view('index.message', compact('list'));
    }

    /**
     * @title 咨询详细内容
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageDetail( $id )
    {
        $message = Message::with('user')->where('id', $id)->first();
        $message[ 'brand' ] = Brand::where('id', $message->user[ 'brand_id' ])->first();

        return view('index.message_detail', compact('message'));
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
     */
    public function prospect()
    {
        //准客户数量
        $prospect = remove_duplicate(Footprint::with('userArticle.article', 'user')->where('uid', session('user_id'))->orderBy('created_at', 'desc')->get());

        return view('index.visitor_prospect', compact('prospect'));
    }

    /**
     * 提醒用户有人对他的文章感兴趣，但未上传自己的二维码
     * @param $user
     */
    public function tipUserQrcode(User $user)
    {
        $content = '有人对你的文章有兴趣并想加你微信，但你尚未上传微信二维码';
        \message($user->openid, 'text', $content);
    }
}