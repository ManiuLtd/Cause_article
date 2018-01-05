<?php

namespace App\Http\Controllers\Admin;
use App\Model\Menu;
use Illuminate\Http\Request;

class MenusController extends CommonController
{
    /**
     * @title  栏目列表
     * @param $menu
     * @return \Illuminate\Http\Response
     */
    public function index(Menu $menu)
    {
        $list = getMenu($menu->orderBy('sort', 'asc')->get()->toArray());
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.menu.index',compact('list','menu','active'));
    }

    /**
     * @title  添加栏目页
     * @param $menu
     * @return \Illuminate\Http\Response
     */
    public function create(Menu $menu)
    {
        $select = getMenu($menu->select('id','pid','title')->orderBy('sort', 'asc')->get()->toArray());
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.menu.add',compact('select','menu','active'));
    }

    /**
     * @title  添加栏目操作
     * @param  $menu
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Menu $menu)
    {
        $store = $menu->create($request->all());
        if ($store) {
            return json_encode(['state'=>0, 'msg'=>'添加完成', 'url'=>route('menu.index')]);
        } else {
            return json_encode(['state'=>401, 'msg'=>'添加出错了喔！']);
        }
    }

    /**
     * @title  更新栏目显示页
     * @param  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $res = $menu;
        $select = getMenu(Menu::select('id','pid','title')->orderBy('sort', 'asc')->get()->toArray());
        $menu = $this->menu;
        $active = $this->active;
        return view('admin.menu.edit',compact('res','select','menu','active'));
    }

    /**
     * @title  更新栏目操作
     * @param  \Illuminate\Http\Request  $request
     * @param  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->all();
        if(!isset($request->display)){
            $data['display'] = 0;
        }
        $update = $menu->update($data);
        if ($update) {
            return json_encode(['state'=>0, 'msg'=>'修改完成', 'url'=>route('menu.index')]);
        } else {
            return json_encode(['state'=>401, 'msg'=>'修改出错了喔！']);
        }
    }

    /**
     * 删除栏目
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Menu $menu)
    {
        if($menu->delete()){
            if($menu->where('pid', $menu->id)->get()){
                $menu->where('pid', $menu->id)->delete();
            }
            return redirect(route('menu.index'));
        }else{
            return redirect(route('menu.index'));
        }
    }
}
