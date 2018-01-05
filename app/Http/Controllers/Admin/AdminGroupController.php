<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminGroup;
use App\Model\Menu;
use Illuminate\Http\Request;
use Auth;

class AdminGroupController extends CommonController
{
    /**
     * 用户组管理
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = AdminGroup::get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.admin.ag_index', compact('list','menu','active'));
    }

    /**
     * 用户组添加页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //获取权限总列表
        $rule = getMenu(Menu::orderBy('sort', 'asc')->get()->toArray());
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.admin.ag_add', compact('rule','menu','active'));
    }

    /**
     * 用户组添加
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AdminGroup $adminGroup)
    {
        $adminGroup->fill($request->all());
        $adminGroup->rule = implode(',', $request->rule);
        if($adminGroup->save()){
            return json_encode(['state'=>0, 'msg'=>'添加用户组完成', 'url'=>route('admin_group.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加用户组失败，请联系管理员']);
        }
    }

    /**
     * 用户组展示页
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 用户组更新页
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminGroup $adminGroup)
    {
        //获取权限总列表
        $rule = getMenu(Menu::orderBy('sort', 'asc')->get()->toArray());
        //获取管理员的权限
        $ruleid = explode(',',$adminGroup->rule);
        $group = $adminGroup;
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.admin.ag_edit', compact('rule','group','ruleid','menu','active'));
    }

    /**
     * 用户组更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminGroup $adminGroup)
    {
        $data = $request->all();
        $data['rule'] =  implode(',',$request->rule);
        $update = $adminGroup->update($data);
        if($update){
            return json_encode(['state'=>0, 'msg'=>'更新用户组完成','url'=>route('admin_group.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新用户组失败，请联系管理员']);
        }
    }

    /**
     * 用户组删除
     * @param AdminGroup $adminGroup
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(AdminGroup $adminGroup)
    {
        if($adminGroup->delete()){
            return redirect(route('admin_group.index'));
        }else{
            return redirect(route('admin_group.index'));
        }
    }
}
