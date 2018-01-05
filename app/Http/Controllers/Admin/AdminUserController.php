<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\AdminGroup;
use Illuminate\Http\Request;

class AdminUserController extends CommonController
{
    /**
     * 用户管理
     * @param $admin Admin
     * @return \Illuminate\Http\Response
     */
    public function index(Admin $admin)
    {
        $list = $admin->with('group')->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.admin.admin',compact('list','menu','active'));
    }

    /**
     * 用户新增页
     * @param $admin
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $select = AdminGroup::get();
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.admin.add', compact('select','menu','active'));
    }

    /**
     * 用户新增操作
     * @param $admin
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Admin $admin)
    {
        $admin->fill($request->all());
        $admin->password = bcrypt($request->password);
        if($admin->save()){
            return json_encode(['state'=>0, 'msg'=>'添加用户完成', 'url'=>route('admin_user.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加用户组失败，请联系管理员']);
        }
    }

    /**
     * @title  更新用户组页
     * @param  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $select = AdminGroup::get();
        $res = $admin;
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.admin.edit', compact('res','select','menu','active'));
    }

    /**
     * 用户更新操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        if($admin->update($data)){
            return json_encode(['state'=>0, 'msg'=>'更新用户完成','url'=>route('admin_user.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新用户失败，请联系管理员']);
        }
    }

    /**
     * 删除用户
     * @param $admin
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Admin $admin)
    {
        if($admin->delete()){
            return redirect(route('admin_group.index'));
        }else{
            return redirect(route('admin_group.index'));
        }
    }
}
