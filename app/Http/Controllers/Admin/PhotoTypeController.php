<?php

namespace App\Http\Controllers\Admin;

use App\Model\PhotoType;
use Illuminate\Http\Request;

class PhotoTypeController extends CommonController
{
    /**
     * @title banner列表
     * @param $photoType
     * @return \Illuminate\Http\Response
     */
    public function index(PhotoType $photoType)
    {
        $list = $photoType->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo_type.index',compact('list','menu','active'));
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
        return view('admin.photo_type.add', compact('menu','active'));
    }

    /**
     * 新增banner操作
     * @param $photoType
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PhotoType $photoType)
    {
        $photoType->fill($request->all());
        if($photoType->save()){
            return json_encode(['state'=>0, 'msg'=>'添加美图类型完成', 'url'=>route('photo_type.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加美图类型失败，请联系管理员']);
        }
    }

    /**
     * @title  更新banner页
     * @param  $photoType
     * @return \Illuminate\Http\Response
     */
    public function edit(PhotoType $photoType)
    {
        $res = $photoType;
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.photo_type.edit', compact('res','menu','active'));
    }

    /**
     * 更新banner操作
     * @param $photoType
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhotoType $photoType)
    {
        $update = $photoType->update($request->all());
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新banner图完成','url'=>route('photo_type.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新banner图失败，请联系管理员']);
        }
    }

    /**
     * 删除banner
     * @param $photoType
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(PhotoType $photoType)
    {
        if($photoType->delete()){
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }
}
