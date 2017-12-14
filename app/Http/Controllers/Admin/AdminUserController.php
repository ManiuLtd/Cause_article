<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\AdminGroup;
use Illuminate\Http\Request;

class AdminUserController extends CommonController
{
    /********用户管理********/
    /**
     * Display a listing of the resource.
     * @用户列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Admin::with('group')->get();
        return view('admin.admin.admin',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $select = AdminGroup::get();
        return view('admin.admin.add', ['select'=>$select, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('gid','account','head','state','password');
        $data['password'] = bcrypt($data['password']);
        if(Admin::create($data)){
            return json_encode(['state'=>0, 'msg'=>'添加用户完成', 'url'=>route('admin_user.index')]);
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
        $find = Admin::find($id);
        $select = AdminGroup::get();
        return view('admin.admin.edit', ['res'=>$find, 'select'=>$select, 'menu'=>$this->menu, 'active'=>$this->active]);
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
        $data = $request->only('gid','account','head','state','password');
        $update = Admin::where('id',$id)->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新用户完成','url'=>route('admin_user.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新用户失败，请联系管理员']);
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
        $del = Admin::find($id);
        if($del->delete()){
            return redirect(route('admin_group.index'));
        }else{
            return redirect(route('admin_group.index'));
        }
    }
}
