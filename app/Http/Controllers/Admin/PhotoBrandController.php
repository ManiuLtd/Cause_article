<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use App\Model\Photo;
use App\Model\PhotoType;
use Illuminate\Http\Request;

class PhotoBrandController extends CommonController
{

    /**
     * 新增banner页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo_brand.add', compact('types','menu','active', 'brands'));
    }

    /**
     * 新增banner操作
     * @param $photo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Photo $photo)
    {
        $photo->fill($request->all());
        if($photo->save()){
            return json_encode(['state'=>0, 'msg'=>'添加美图类型完成', 'url'=>route('photo.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加美图类型失败，请联系管理员']);
        }
    }

    /**
     * @title  更新banner页
     * @param  $photo_brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo_brand)
    {
        $brands = Brand::all();
        $res = $photo_brand;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo_brand.edit', compact('brands','res','menu','active'));
    }

    /**
     * 更新banner操作
     * @param $photo_brand
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo_brand)
    {
        $update = $photo_brand->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新美图完成','url'=>route('photo.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新美图失败，请联系管理员']);
        }
    }
}
