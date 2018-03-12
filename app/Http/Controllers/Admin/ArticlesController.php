<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\ArticleType;
use App\Model\Brand;
use App\Model\ExtensionArticle;
use App\Model\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class ArticlesController extends CommonController
{
    /**
     * 文章列表
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Article $article)
    {
        $where = [];
        switch ($request->key) {
            case 'title':
                if($request->value) $where['title'] = $request->value;
                if($request->type != 4) $where['type'] = $request->type;
                if($request->brand) $where['brand_id'] = $request->brand;
                break;
        }
        $list = $article->with('brand', 'article_type')->where($where)->orderBy('created_at', 'desc')->paginate(12);
        //品牌列表
        $brand_list = Brand::all();
        $types = ArticleType::all();
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.articles.index',compact('list','brand_list', 'types','menu','active'));
    }

    /**
     * 新增文章页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $brand = Brand::get();
        $types = ArticleType::get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.articles.add', compact('brand','types','menu','active'));
    }

    /**
     * 新增文章操作
     * @param Request $request
     * @param Article $article
     * @return string
     */
    public function store(Request $request, Article $article)
    {
        $article->fill($request->all());
        $article->read = rand(500, 1000);
        if($article->save()){
            return json_encode(['state'=>0, 'msg'=>'添加文章完成', 'url'=>route('articles.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加文章失败，请联系管理员']);
        }
    }

    /**
     * @title  更新文章页
     * @param  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $res = $article;
        $brand = Brand::get();
        $types = ArticleType::get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.articles.edit', compact('res','brand','types','menu','active'));
    }

    /**
     * 更新文章操作
     * @param Request $request
     * @param Article $article
     * @return string
     */
    public function update(Request $request, Article $article)
    {
        $update = $article->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新文章完成','url'=>route('articles.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新文章失败，请联系管理员']);
        }
    }

    /**
     * 删除文章操作
     * @param $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        if($article->delete()){
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }

    /**
     * 提交好文章列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodArticleList()
    {
        $lists = ExtensionArticle::orderBy('id', 'desc')->paginate(15);
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.good_article', compact('lists', 'menu', 'active'));
    }

    /**
     * 审核文章
     * @param Request $request
     * @param ExtensionArticle $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function examine( Request $request, ExtensionArticle $article )
    {
        $article->where('id', $article->id)->update(['state' => 1]);

        $openid = User::where('id', $article->user_id)->value('openid');

        //推送【推荐会员成功提醒】模板消息
        $app = new Application(config('wechat'));
        $msg = [
            "first"     => "您好，您提交的文章已审核通过，已添加到文章库，快去看看吧！",
            "keyword1"  => "审核通过",
            "keyword2"  => date('Y-m-d H:i',time()),
            "remark"    => "点击查看详情"
        ];
        template_message($app, $openid, $msg, config('wechat.template_id.examine_article'), $request->url);

        return response()->json(['state' => 0, 'error' => '审核通过']);
    }

    /**
     * 删除提交的好文章
     * @param ExtensionArticle $article
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteGoodArticle(ExtensionArticle $article)
    {
        $article->delete();

        return redirect()->back();
    }
}
