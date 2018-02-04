<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\AdminGroup;
use Illuminate\Http\Request;

class AdminUserController extends CommonController
{
    /**
     * 用户管理
     * @param $admin_user Admin
     * @return \Illuminate\Http\Response
     */
    public function index(Admin $admin_user)
    {
        $list = $admin_user->with('group')->get();
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.admin.admin',compact('list','menu','active'));
    }

    /**
     * 用户新增页
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
     * @param $admin_user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Admin $admin_user)
    {
        $admin_user->fill($request->all());
        $admin_user->password = bcrypt($request->password);
        if($admin_user->save()){
            return json_encode(['state'=>0, 'msg'=>'添加用户完成', 'url'=>route('admin_user.index')]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'添加用户组失败，请联系管理员']);
        }
    }

    /**
     * @title  更新用户组页
     * @param  $admin_user
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin_user)
    {
        $select = AdminGroup::get();
        $res = $admin_user;
        $menu = $this->menu;
        $active = $this->active;

        return view('admin.admin.edit', compact('res','select','menu','active'));
    }

    /**
     * 用户更新操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $admin_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin_user)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        if($admin_user->update($data)){
            return json_encode(['state'=>0, 'msg'=>'更新用户完成']);
        }else{
            return json_encode(['state'=>401, 'msg'=>'更新用户失败，请联系管理员']);
        }
    }

    /**
     * 删除用户
     * @param $admin_user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Admin $admin_user)
    {
        if($admin_user->delete()){
            return redirect(route('admin_group.index'));
        }else{
            return redirect(route('admin_group.index'));
        }
    }
}
