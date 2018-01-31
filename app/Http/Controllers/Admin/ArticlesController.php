<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\ArticleType;
use App\Model\Brand;
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
}
