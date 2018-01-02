<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 下午 3:48
 */

namespace App\Http\Controllers\Index;

use App\Model\Article;
use App\Model\Footprint;
use App\Model\User;
use App\Model\UserArticles;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Wxpay\Wechat;

class ArticleController extends CommonController
{
    /**
     * @title 搜索文章列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchArticle()
    {
        $key = request()->key;
        $list = Article::where('title','like',"%$key%")->orderBy('created_at','desc')->get();
        return view('index.article_search',['list'=>$list]);
    }

    /**
     * @title 公共文章详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleDetails($id)
    {
        $res = Article::with('brand')->where('id',$id)->first();
        //文章浏览数+1
        Article::where('id',$id)->increment('read',1);
        //判断用户是否已拥有该文章
//        $user_article = UserArticles::where(['uid'=>session()->get('user_id'),'aid'=>$id])->first();
        //微信分享配置
        $package = wecahtPackage();
        return view('index.article_details',compact('res','package','user_article'));
    }

    /**
     * @title 公共文章分享数+1
     * @param $id
     */
    public function articleShare($id)
    {
        Article::where('id',$id)->increment('share',1);
    }

    /**
     * @title 在文章中停留时间
     * @param $uid 浏览用户id
     * @param $uaid 用户文章表的主键id
     */
    public function footprintTime($uid, $uaid)
    {
        Footprint::where(['uid'=>$uid,'uaid'=>$uaid])->update(['residence_time'=>'停留时间']);
    }

    /**
     * 使公共文章变为我的文章
     * @param $uid
     * @param $aid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function becomeMyArticle($uid, $aid)
    {
        $userinfo = User::find($uid);
        if($userinfo['subscribe'] == 1){
            if ($uarticle = UserArticles::where(['uid'=>$uid,'aid'=>$aid])->first()) {
                return redirect(route('user_article_details', ['id' => $uarticle->id]));//跳到个人此文章详细页
            } else {
                $id = UserArticles::insertGetId(['uid' => $uid, 'aid' => $aid]);
                return redirect(route('user_article_details', ['id' => $id]));//跳到个人此文章详细页
            }
        }else{
            //创建临时二维码（参数为str类型）
            $options = config('wechat.wechat_config');
            $app = new Application($options);
            $qrcode = $app->qrcode;
            $result = $qrcode->temporary("$uid|$aid");
            $url = $qrcode->url($result->ticket);
            return view('index.become_my_article',['imgurl'=>$url]);//显示扫二维码关注公众号才能使文章变成自己的页面
        }
    }


}