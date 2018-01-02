<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8 0008
 * Time: 下午 5:35
 */

namespace App\Http\Controllers\Index;

use App\Model\{Article,Banner,Brand,Footprint,Report,User,UserArticles};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IndexController extends CommonController
{
    /**
     * @title 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //banner图
        if(Cache::has('banner')) {
            $banner_list = Cache::get('banner');
        } else {
            $banner_list = Banner::all();
            Cache::put('banner', $banner_list, 30);
        }

        $user_brand = User::where('id', session()->get('user_id'))->value('brand_id');
        $type = [ 1, 2, 3, 4 ];
        if ( !empty($request->input('type')) ) $type = [ $request->input('type'), 4 ];
        $list = Article::orderBy('created_at', 'desc')->whereIn('type', $type)
            ->when($user_brand, function ( $query ) use ( $user_brand ) {
            //根据用户选择的品牌显示文章
            return $query->whereIn('brand_id', [ 0, $user_brand ]);
        })->get();

        return view('index.index', compact('banner_list', 'list', 'user'));
    }

    /**
     * @title  品牌列表接口
     * @return string
     */
    public function brandList()
    {
        $brand_list = Brand::select('id','name','domain')->orderBy('domain','asc')->get();
        return response()->json(['state'=>0, 'brand_list'=>$brand_list->toArray()]);
    }

    /**
     * @title 完善资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function perfectInformation(Request $request)
    {
        $data = ['wc_nickname'=>$request->wc_nickname,'phone'=>$request->phone,'brand_id'=>$request->brand_id];
        if(User::where('id',$request->id)->update($data)){
            return response()->json(['state'=>0]);
        }
    }

    /**
     * @title 举报页面
     * @param $article_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report($article_id)
    {
        return view('index.report');
    }

    /**
     * @title 填写举报内容
     * @param $article_id
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportText($article_id, $type)
    {
        return view('index.report_text');
    }

    /**
     * @title 提交举报
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportPost(Request $request)
    {
        $data = [
            'aid'   =>  $request->aid,
            'uid'   =>  session()->get('user_id'),
            'message'   =>  $request->message,
            'type'  =>  $request->type,
            'created_at'    =>  date('Y-m-d H:i:s', time())
        ];
        if($request->atype == 1) {
            $data['aid'] = $request->aid;
            $url = route('article_details',['id'=>$request->aid]);
        } elseif($request->atype == 2) {
            $ua = UserArticles::where('id',$request->aid)->select('aid')->first();
            $data['aid'] = $ua->aid;
            $url = route('user_article_details',['id'=>$request->aid]);
        }
        if (Report::create($data)) {
            return response()->json(['state'=>0, 'errormsg'=>'已举报改文章','url'=>$url]);
        } else {
            return response()->json(['state'=>401, 'errormsg'=>'举报文章失败','url'=>$url]);
        }
    }
}