<?php

namespace App\Http\Controllers\Admin;

use App\Model\Banner;
use Illuminate\Http\Request;

class BannerController extends CommonController
{
    /**
     * @title banner列表
     * @param $banner
     * @return \Illuminate\Http\Response
     */
    public function index(Banner $banner)
    {
        $list = $banner->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.banner.index',compact('list','menu','active'));
    }

    /**
     * 新增banner页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.banner.add', compact('menu','active'));
    }

    /**
     * 新增banner操作
     * @param $banner
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Banner $banner)
    {
        $banner->fill($request->all());
        if($banner->save()){
            return json_encode(['state'=>0, 'msg'=>'添加banner图完成', 'url'=>route('banner.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加banner图失败，请联系管理员']);
        }
    }

    /**
     * @title  更新banner页
     * @param  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $res = $banner;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.banner.edit', compact('res','menu','active'));
    }

    /**
     * 更新banner操作
     * @param $banner
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        $update = $banner->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新banner图完成','url'=>route('banner.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新banner图失败，请联系管理员']);
        }
    }

    /**
     * 删除banner
     * @param $banner
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        if($banner->delete()){
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }
}
