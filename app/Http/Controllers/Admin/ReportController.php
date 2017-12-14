<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/23 0023
 * Time: 上午 11:46
 */

namespace App\Http\Controllers\Admin;


use App\Model\Article;
use App\Model\Report;
use App\Model\User;
use Illuminate\Http\Request;

class ReportController extends CommonController
{

    public function index(Request $request)
    {
        $where = [];
        if(!empty($request->tyep)) $where['type'] = $request->type;
        if(!empty($request->value)){
            $key = $request->key;
            switch ($key) {
                case 'article':
                    $article = Article::where('title',$request->value)->select('id')->first();
                    if($article) $where['aid'] = $article->id;
                    break;
                case 'user':
                    $user = User::where('wc_nickname',$request->value)->select('id')->first();
                    if($user) $where['uid'] = $user->id;
                    break;
            }
        }
        $list = Report::with('article','user')->where($where)->orderBy('created_at','desc')->paginate(15);
        return view('admin.report.index',['list'=>$list,'menu'=>$this->menu,'active'=>$this->active]);
    }
}