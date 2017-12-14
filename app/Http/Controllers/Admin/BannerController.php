<?php

namespace App\Http\Controllers\Admin;

use App\Model\Banner;
use Illuminate\Http\Request;

class BannerController extends CommonController
{
    /********用户管理********/
    /**
     * Display a listing of the resource.
     * @title 品牌列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Banner::get();
        return view('admin.banner.index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner.add', ['menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        if(Banner::create($data)){
            return json_encode(['state'=>0, 'msg'=>'添加banner图完成', 'url'=>route('banner.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加banner图失败，请联系管理员']);
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
        $find = Banner::find($id);
        return view('admin.banner.edit', ['res'=>$find, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token','_method');
        $update = Banner::where('id',$id)->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新banner图完成','url'=>route('banner.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新banner图失败，请联系管理员']);
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
        $del = Banner::find($id);
        if($del->delete()){
            return redirect(route('banner.index'));
        }else{
            return redirect(route('banner.index'));
        }
    }
}
