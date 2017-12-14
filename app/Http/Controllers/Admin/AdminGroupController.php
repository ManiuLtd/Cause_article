<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminGroup;
use App\Model\Menu;
use Illuminate\Http\Request;
use Auth;

class AdminGroupController extends CommonController
{
    /********用户组管理********/
    /**
     * Display a listing of the resource.
     * @用户组列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = AdminGroup::get();
        return view('admin.admin.ag_index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //获取权限总列表
        $rule = getMenu(Menu::orderBy('sort', 'asc')->get()->toArray());
        return view('admin.admin.ag_add', ['rule'=>$rule, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('title','state','rule');
        $data['rule'] = implode(',',$data['rule']);
        if(AdminGroup::create($data)){
            return json_encode(['state'=>0, 'msg'=>'添加用户组完成', 'url'=>route('admin_group.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加用户组失败，请联系管理员']);
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
        //获取权限总列表
        $rule = getMenu(Menu::orderBy('sort', 'asc')->get()->toArray());
        //获取管理员的权限
        $find = AdminGroup::where('id',$id)->first();
        $ruleid = explode(',',$find->rule);
        return view('admin.admin.ag_edit', ['rule'=>$rule, 'group'=>$find, 'ruleid'=>$ruleid, 'menu'=>$this->menu, 'active'=>$this->active]);
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
        $data = $request->only('title','state','rule');
        $data['rule'] =  implode(',',$data['rule']);
        $update = AdminGroup::where('id',$id)->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新用户组完成','url'=>route('admin_group.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新用户组失败，请联系管理员']);
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
        $del = AdminGroup::find($id);
        if($del->delete()){
            return redirect(route('admin_group.index'));
        }else{
            return redirect(route('admin_group.index'));
        }
    }
}
