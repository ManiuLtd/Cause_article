<?php

namespace App\Http\Controllers\Admin;
use App\Model\Menu;
use Illuminate\Http\Request;

class MenusController extends CommonController
{
    /**
     * Display a listing of the resource.栏目资源控制器
     * @title  栏目列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = getMenu(Menu::orderBy('sort', 'asc')->get()->toArray());
        return view('admin.menu.index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Show the form for creating a new resource.
     * @title  添加栏目页
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $select = getMenu(Menu::select('id','pid','title')->orderBy('sort', 'asc')->get()->toArray());
        return view('admin.menu.add',['select'=>$select,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Store a newly created resource in storage.
     * @title  添加栏目操作
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['pid','title','icon','url','sort','display']);
        $store = Menu::create($input);
        if ($store) {
            return json_encode(['state'=>0, 'msg'=>'添加完成', 'url'=>route('menu.index')]);
        } else {
            return json_encode(['state'=>401, 'msg'=>'添加出错了喔！']);
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
     * @title  修改栏目显示页
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = Menu::find($id);
        $select = getMenu(Menu::select('id','pid','title')->orderBy('sort', 'asc')->get()->toArray());
        return view('admin.menu.edit',['select'=>$select,'res'=>$res,'menu'=>$this->menu,'active'=>$this->active]);
    }

    /**
     * Update the specified resource in storage.
     * @title  更新栏目
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->only(['pid','title','icon','url','sort','display']);
        if(!isset($input['display'])){
            $input['display'] = 0;
        }
        $update = Menu::where('id',$id)->update($input);
        if ($update) {
            return json_encode(['state'=>0, 'msg'=>'修改完成', 'url'=>route('menu.index')]);
        } else {
            return json_encode(['state'=>401, 'msg'=>'修改出错了喔！']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @title  删除栏目
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Menu::find($id);
        if($del->delete()){
            if(Menu::where('pid',$id)->get()){
                Menu::where('pid',$id)->delete();
            }
            return redirect(route('menu.index'));
        }else{
            return redirect(route('menu.index'));
        }
    }
}
