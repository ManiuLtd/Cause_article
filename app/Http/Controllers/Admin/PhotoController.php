<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use App\Model\Photo;
use App\Model\PhotoType;
use Illuminate\Http\Request;

class PhotoController extends CommonController
{
    /**
     * @title banner列表
     * @param $photo
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Photo $photo)
    {
        $where = [];
        if($request->name) $where['name'] = $request->name;
        if($request->type) $where['type_id'] = $request->type;
        if($request->brand_id) $where['brand_id'] = $request->brand_id;
        $list = $photo->with('type', 'brand')->where($where)->orderBy('created_at', 'desc')->paginate(10);
        $menu = $this->menu;
        $active = $this->active;
        $brands = Brand::get();
        $types = PhotoType::get();

        return view('admin.photo.index',compact('list', 'menu', 'active', 'brands', 'types'));
    }

    /**
     * 新增banner页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = PhotoType::all();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo.add', compact('types','menu','active'));
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
        $photo->url = $request->url;
        if($photo->save()){
            return json_encode(['state'=>0, 'msg'=>'添加美图类型完成', 'url'=>route('photo.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加美图类型失败，请联系管理员']);
        }
    }

    /**
     * @title  更新banner页
     * @param  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        $types = PhotoType::all();
        $res = $photo;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo.edit', compact('types','res','menu','active'));
    }

    /**
     * 更新banner操作
     * @param $photo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $data = $request->all();
        if($photo->url != $request->photo) {
            $data['url'] = $request->url;
        }
        $update = $photo->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新美图完成','url'=>route('photo.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新美图失败，请联系管理员']);
        }
    }

    /**
     * 删除banner
     * @param $photo
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Photo $photo)
    {
        if($photo->delete()){
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }
}
