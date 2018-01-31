<?php

namespace App\Http\Controllers\Admin;

use App\Model\ArticleType;
use Illuminate\Http\Request;

class ArticleTypeController extends CommonController
{
    /**
     * @title 品牌列表
     * @param $article_type
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleType $article_type)
    {
        $list = $article_type->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.article_type.index',compact('list','menu','active'));
    }

    /**
     * 新增品牌页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.article_type.add', compact('menu','active'));
    }

    /**
     * 新增品牌操作
     * @param  $article_type
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ArticleType $article_type)
    {
        if($article_type->create($request->all())){
            return json_encode(['state'=>0, 'msg'=>'添加文章类型完成', 'url'=>route('article_type.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加文章类型失败，请联系管理员']);
        }
    }

    /**
     * @title  更新品牌页
     * @param  $article_type
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleType $article_type)
    {
        $res = $article_type;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.article_type.edit', compact('res','menu','active'));
    }

    /**
     * 更新品牌操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $article_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleType $article_type)
    {
        $update = $article_type->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新文章类型完成','url'=>route('article_type.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新文章类型失败，请联系管理员']);
        }
    }

    /**
     * 删除品牌操作
     * @param $article_type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(ArticleType $article_type)
    {
        if($article_type->delete()){
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }
}
