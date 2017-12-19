<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\Brand;
use Illuminate\Http\Request;

class ArticlesController extends CommonController
{
    /********用户管理********/
    /**
     * Display a listing of the resource.
     * @用户列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        switch ($request->key) {
            case 'title':
                if($request->value) $where['title'] = $request->value;
                if($request->type != 4) $where['type'] = $request->type;
                if($request->brand) $where['brand_id'] = $request->brand;
                break;
        }
        $list = Article::with('brand')->where($where)->orderBy('created_at', 'desc')->paginate(12);
        //品牌列表
        $brand_list = Brand::all();
        return view('admin.articles.index',['list'=>$list,'brand_list'=>$brand_list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::get();
        return view('admin.articles.add', ['brand'=>$brand, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Article $article)
    {
        $data = $request->all();
        $data['created_at'] = date('Y-m-d H:i:s',time());
        if($article->create($data)){
            return json_encode(['state'=>0, 'msg'=>'添加文章完成', 'url'=>route('articles.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加文章失败，请联系管理员']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @title  修改用户组页
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $find = Article::find($id);
        $brand = Brand::get();
        return view('admin.articles.edit', ['res'=>$find, 'brand'=>$brand, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $data = $request->except('_token','_method');
//        $data['details'] = $request->editorValue;
        $update = $article->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新文章完成','url'=>route('articles.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新文章失败，请联系管理员']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Article::find($id);
        if($del->delete()){
            return redirect(route('articles.index'));
        }else{
            return redirect(route('articles.index'));
        }
    }
}
