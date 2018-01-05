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
    public function index()
    {
        $list = UserArticles::with('article')->where('uid', \Session::get('user_id'))->orderBy('created_at', 'desc')->get();

        return view('index.user_article', [ 'list' => $list ]);
    }

    /**
     * 我的文章详细页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetail( UserArticles $articles )
    {
        $uid = session()->get('user_id');
        $addfootid = '';
        $fkarticle = '';
        $uarticle = $articles->with('user', 'article')->where('id', $articles->id)->first();

        //获取品牌
        $brand = Brand::find($uarticle->user->brand_id);

        if ( $uarticle->uid != $uid ) {
            //用户文章第一次阅读则推送文本消息给该文章拥有者
            $cachename = $articles->id . $uarticle->user[ 'openid' ];
            if ( !Cache::has("$cachename") ) {
                //推送消息
                $context = "有人对你的头条感兴趣！还不赶紧看看是谁~\n\n头条标题：《{$uarticle->article[ 'title' ]}》\n\n<a href='http://bw.eyooh.com/visitor_record'>【点击这里】查看谁对我的头条感兴趣>></a>";
                message($uarticle->user[ 'openid' ], 'text', $context);
                Cache::put("$cachename", 1, 60);
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
            $addfootid = Footprint::insertGetId([ 'uid' => $uarticle->uid, 'see_uid' => $uid, 'uaid' => $articles->id, 'created_at' => date('Y-m-d H:i:s', time()), 'type' => 1 ]);

            //判断访客是否已拥有该文章
            $fkarticle = UserArticles::where([ 'aid' => $uarticle->article[ 'id' ], 'uid' => $uid ])->first();
        }

        $uarticle[ 'brand' ] = Brand::find($uarticle->article[ 'brand_id' ]);

        //微信分享配置
        $package = wecahtPackage();

        //判断是否是会员或会员已过期
        $member_time = Carbon::parse($uarticle->user[ 'membership_time' ])->gt(Carbon::parse('now'));

        return view('index.user_article_details', [ 'res' => $uarticle, 'brand' => $brand, 'footid' => $addfootid, 'package' => $package, 'member_time' => $member_time, 'fkarticle' => $fkarticle ]);
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
     */
    public function userArticleShare( UserArticles $articles )
    {
        if ( $articles->uid != session()->get('user_id') ) {
            if ( $articles->isrs == 0 ) $articles->update([ 'isrs' => 1 ]);
            //用户文章分享数+1
            $articles->increment('share', 1);
            $data = [
                'uid'     => $articles->uid,
                'see_uid' => session()->get('user_id'),
                'uaid'    => $articles->id,
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
     * @title 用户在用户文章详细页停留的时间
     * @param Request $request
     */
//    public function userArticleTime(Request $request)
//    {
//        if($request->uid != session()->get('user_id')){
//            $farticle = UserArticles::find($request->id);
//            if($farticle->isrs == 0) UserArticles::where('id',$request->id)->update(['isrs'=>1]);
//            $data = [
//                'uid'      =>  $request->uid,
//                'see_uid'  =>  session()->get('user_id'),
//                'uaid'     =>  $request->id,
//                'residence_time'  =>  $request->time,
//                'type'     =>  1
//            ];
//            Footprint::create($data);
//        }
//    }

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
        $member_time = User::where('id', $uid)->select('membership_time')->first();

        $list = UserArticles::with('article', 'footprint')->where([ 'uid' => $uid, 'first_read' => 1 ])->orderBy('created_at', 'desc')->get()->toArray();
        foreach ( $list as $key => $value ) {
            //统计新访客
            $list[ $key ][ 'new_count' ] = Footprint::where([ 'uaid' => $value[ 'id' ], 'new' => 1 ])->count();

            //去除重复用户获取单个用户id
            $user_list = remove_duplicate($value[ 'footprint' ]);
            foreach ( $user_list as $k => $v ) {
                //获取用户头像
                if ( Carbon::parse($member_time->membership_time)->gt(Carbon::parse('now')) ) {
                    //已开通会员
                    $list[ $key ][ 'user' ][ $k ] = User::where('id', $v[ 'see_uid' ])->select('head')->first();
                } else {
                    //未开通会员
                    $list[ $key ][ 'user' ][ $k ][ 'head' ] = '/head.png';
                }
            }
        }

        return view('index.visitor_record', compact('list', 'user_list'));
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
        } ])->where('id', $id)->first();
        $footprint = $visitor_article->footprint;
        foreach ( $footprint as $key => $value ) {
            Footprint::where('id', $value[ 'id' ])->update([ 'new' => 0 ]);
            $footprint[ $key ][ 'user' ] = User::where('id', $value[ 'see_uid' ])->select('head', 'wc_nickname')->first();
        }

        return view('index.visitor_detail', [ 'res' => $visitor_article, 'footprint' => $footprint ]);
    }

    /**
     * @title 在线询问用户文章
     * @param $id '用户文章表id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chatroom( $id )
    {
        $res = UserArticles::with('user')->where('id', $id)->first();

        return view('index.chatroom', compact('res'));
    }

    /**
     * @title  提交在线咨询信息
     * @param $request Request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submitMessage( Request $request )
    {
        $data = $request->except('_token', 'uaid');
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

            return redirect(route('user_article_details', [ 'id' => request()->uaid ]));
        }
    }

    /**
     * 咨询列表
     * @param $message Message
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageList(Message $message)
    {
        $list = $message->with('user')->where('uid', \Session::get('user_id'))->get();

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
     * @param $uid  '查找的用户id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function connection( $uid )
    {
        //阅读
        $read = Footprint::where([ 'uid' => session()->get('user_id'), 'see_uid' => $uid, 'type' => 1 ])->count();
        //分享
        $share = Footprint::where([ 'uid' => session()->get('user_id'), 'see_uid' => $uid, 'type' => 2 ])->count();
        //最近访问的时间
        $res = Footprint::with([ 'user' => function ( $query ) {
            $query->select('id', 'head', 'wc_nickname');
        } ])->where([ 'uid' => session()->get('user_id'), 'see_uid' => $uid ])->orderBy('created_at', 'desc')->limit(1)->get()->toArray();

        return view('index.connection', compact('read', 'share', 'res'));
    }
}