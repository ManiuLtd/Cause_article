<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use Illuminate\Http\Request;

class BrandController extends CommonController
{
    /**
     * @title 品牌列表
     * @param $brand
     * @return \Illuminate\Http\Response
     */
    public function index(Brand $brand)
    {
        $list = $brand->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.brand.index',compact('list','menu','active'));
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
        return view('admin.brand.add', compact('menu','active'));
    }

    /**
     * 新增品牌操作
     * @param $brand
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Brand $brand)
    {
        if($brand->create($request->all())){
            return json_encode(['state'=>0, 'msg'=>'添加品牌完成', 'url'=>route('brand.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加品牌失败，请联系管理员']);
        }
    }

    /**
     * @title  更新品牌页
     * @param  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $res = $brand;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.brand.edit', compact('res','menu','active'));
    }

    /**
     * 更新品牌操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $update = $brand->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新品牌完成','url'=>route('brand.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新品牌失败，请联系管理员']);
        }
    }

    /**
     * 删除品牌操作
     * @param $brand
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        if($brand->delete()){
            return redirect(route('brand.index'));
        }else{
            return redirect(route('brand.index'));
        }
    }
}
