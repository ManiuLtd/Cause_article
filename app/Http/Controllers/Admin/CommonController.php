<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29 0029
 * Time: 下午 5:23
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\AdminGroup;
use App\Model\Menu;
use Illuminate\Support\Facades\Auth;


class CommonController extends Controller
{
    public $menu;

    public $active;

    public function __construct()
    {
        //必须使用以下方式才能在里面获取session
        $this->middleware(function ($request, $next) {
            $aid = Auth::user()->id;
            $gid = Admin::where('id', $aid)->value('gid');
            //获取用户组权限ID
            $adminrule = explode(',', AdminGroup::where('id', $gid)->value('rule'));
            //递归输出栏目
            $list = getMenu(Menu::whereIn('id',$adminrule)->orderBy('sort', 'asc')->get()->toArray());
            $url = ucfirst($_SERVER['REQUEST_URI']);
            v('headtitle',Menu::where('url',$url) -> value('title'));
            $this->menu = $list;

            //侧边栏定位class
            $url = $_SERVER['REQUEST_URI'];
            $active = Menu::where('url', $url)->first();
            if($active){
                session()->get('menu',$active->toArray());
                $this->active = $active->toArray();
            }else{
                $this->active = session()->get('menu');
            }
            return $next($request);
        });
    }
}