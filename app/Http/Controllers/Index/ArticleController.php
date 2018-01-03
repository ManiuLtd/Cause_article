<?php
/**
 * 公共文章控制器
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
    public function articleDetails(Article $article)
    {
        $res = Article::with('brand')->where('id', $article->id)->first();
        //文章浏览数+1
        $article->increment('read',1);
        //判断用户是否已拥有该文章
//        $user_article = UserArticles::where(['uid'=>session()->get('user_id'),'aid'=>$id])->first();
        //微信分享配置
        $package = wecahtPackage();
        return view('index.article_details',compact('res','package','user_article'));
    }

    /**
     * @title 公共文章分享数+1
     */
    public function articleShare(Article $article)
    {
        $article->increment('share',1);
    }

    /**
     * 使公共文章变为我的文章
     * @param $user_id      '当前用户id'
     * @param $article_id   '分享文章id'
     * @param $pid          '分享用户id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function becomeMyArticle($user_id, $article_id, $pid = 0)
    {
        $userinfo = User::find($user_id);
        if($userinfo['subscribe'] == 1){
            if ($uarticle = UserArticles::where(['uid'=>$user_id,'aid'=>$article_id])->first()) {
                return redirect(route('user_article_details', ['id' => $uarticle->id]));//跳到个人此文章详细页
            } else {
                $id = UserArticles::insertGetId(['uid' => $user_id, 'aid' => $article_id]);
                return redirect(route('user_article_details', ['id' => $id]));//跳到个人此文章详细页
            }
        }else{
            //创建临时二维码（参数为str类型）
            $options = config('wechat');
            $app = new Application($options);
            $qrcode = $app->qrcode;
            $result = $qrcode->temporary("$user_id|$article_id|$pid");
            $url = $qrcode->url($result->ticket);
            return view('index.become_my_article',['imgurl'=>$url]);//显示扫二维码关注公众号才能使文章变成自己的页面
        }
    }


}