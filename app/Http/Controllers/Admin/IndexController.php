<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29 0029
 * Time: 下午 6:00
 */

namespace App\Http\Controllers\Admin;

use App\Model\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends CommonController
{

    public function index()
    {
        $list = DB::table('admin_logs')->orderBy('add_time','desc')->paginate(15);
        return view('admin.index.index', ['list'=>$list, 'menu'=>$this->menu, 'active'=>$this->active]);
    }

    public function webConfig(Request $request)
    {
        if($request->post()){
            $config = Config::find(1);
            $config->web_tip = $request->web_tip;
            if($config->save()){
                return redirect('web_config');
            }
        }else{
            $res = Config::find(1);
            return view('admin.index.web_config',['res'=>$res, 'menu'=>$this->menu, 'active'=>$this->active]);
        }
    }

    /**
     * @title 后台上传图片
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $file_name = $request->file_name;
        $daytime = date('Ymd',time());
        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return json_encode(['error' => '文件格式错误！']);
        }
        $destinationPath = "../public_html/uploads/$file_name/$daytime/";
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $ret = $file->move($destinationPath, $fileName);
        if($ret){
            return json_encode(['state'=>0, 'msg'=>'上传成功', 'saveName'=>"$file_name/$daytime/$fileName"]);
        }else{
            return json_encode(['state'=>401, 'msg'=>'上传失败']);
        }
    }

    /**
     * @title   文本编辑器上传图片
     */
    public function ckeditor()
    {
        $file = request()->file('upload');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $daytime = date('Ymd',time());
        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return json_encode(['error' => '文件格式错误！']);
        }

        $destinationPath = "../public_html/uploads/ckeditor_article/$daytime/";
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $ret = $file->move($destinationPath, $fileName);
        if($ret){
            // 成功上传后 获取上传信息
            $callback = $_REQUEST["CKEditorFuncNum"];
            return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".'http://bw.eyooh.com/uploads/ckeditor_article/'.$daytime.'/'.$fileName."','');</script>";
        }else{
            // 上传失败获取错误信息
            return '上传出错';
        }
    }
}