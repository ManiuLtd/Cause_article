<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 下午 5:41
 */

namespace App\Http\Controllers\Index;

use App\Http\Controllers\TraitFunction\FunctionUser;
use App\Jobs\extensionImage;
use App\Model\Admin;
use App\Model\Brand;
use App\Model\Footprint;
use App\Model\Order;
use App\Model\Photo;
use App\Model\User;
use App\Model\UserArticles;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends CommonController
{
    use FunctionUser;

    /**
     * @title  个人中心
     * @param $type 'become_dealer->招商，become_extension->运营'
     * @param $dealer '推广id'
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View become_dealer
     */
    public function index($type = '', $dealer = '')
    {
        $uid = session('user_id');
        $user = User::withCount('user_article', 'user_foot')->where('id', $uid)->first();
        //未被员工推广的用户才可以关联
        if($user->type == 1 && empty($user->admin_id) && empty($user->admin_type)) {
            //通过招商链接进入（成为经销商并关联招商员工）
            if($type == 'become_dealer') User::where('id', $uid)->update(['type' => 2, 'admin_id' => $dealer,'admin_type' => 1]);
            //通过运营链接进入 (该用户关联运营员工)
            if($type == 'become_extension') {
                if(!$user->subscribe) {
                    $data = [
                        'admin_id' => $dealer,
                        'admin_type' => 2
                    ];
                    Cache::put($user->openid, $data, 60 * 24 * 10);
                } else {
                    User::where('id', $uid)->update([ 'admin_id' => $dealer, 'admin_type' => 2 ]);
                }
            }
            //普通用户推广链接进来的
            if($type == 'ex_user' && $dealer != $user->id) {
                $puser = User::select('admin_id', 'admin_type')->where('id', $dealer)->first();
                if(!$user->subscribe) {
                    $data = [
                        'extension_id' => $dealer,
                        'admin_id' => $puser->admin_id,
                        'admin_type' => $puser->admin_type
                    ];
                    Cache::put($user->openid, $data, 60 * 24 * 10);
                } else {
                    User::where('id', $uid)->update([ 'extension_id' => $dealer, 'admin_id' => $puser->admin_id, 'admin_type' => $puser->admin_type ]);
                }
            }
        }

        //订单轮播展示
        $orders = Order::with(['user'=>function($query){
            $query->select('id','wc_nickname');
        }])->where(['state' => 1, 'refund_state' => 0])->orderBy('pay_time', 'desc')->limit(10)->get();

        //美图列表
        $brand_id = User::where('id', session('user_id'))->value('brand_id');
        if($brand_id) {
            $brand_photo = Photo::where('brand_id', $brand_id)->get();
            if($brand_photo->count() >= 4) {
                $photos = $brand_photo->random(4);
            } else {
                $brand = Brand::where('id', '<>', $brand_id)->get();
                $photos = Photo::whereNotIn('brand_id', $brand->pluck('id')->all())->get()->random(4);
            }
        } else {
            $photos = Photo::get()->random(4);
        }

        return view('index.user_center', compact('user', 'orders', 'photos'));
    }

    /**
     * 用户头像和二维码转base64
     * @return \Illuminate\Http\JsonResponse
     */
    public function headQrcodeBase64()
    {
        $res = User::where('id', session('user_id'))->select('id', 'type', 'openid')->first();
        $qrcode = Cache::remember('user_qrcode'.$res->openid, 60 * 24 * 10, function () use($res) {
            $url = app(User::class)->createQrcode($res);
            //二维码转base64位
            $pic = "data:image/jpeg;base64," . base64_encode(file_get_contents($url));

            return $pic;
        });
        $head = Cache::remember('user_head'.$res->openid, 60 * 24 * 10, function () {
            //头像转base64
            $head = session('head_pic');
            if(str_contains($head, 'qlogo.cn')) { //strpos
                $content = file_get_contents($head);
                $head =  'data:image/jpeg;base64,' . base64_encode($content);
            } else {
                $content = file_get_contents(config('app.url').$head);
                $head =  'data:image/jpeg;base64,' . base64_encode($content);
            }

            return $head;
        });

        return response()->json(['qrcode'=> $qrcode, 'head' => $head]);
    }

    /**
     * @title  用户基本信息及修改
     * @param $request Request
     * @param $user User
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userBasic(Request $request, User $user)
    {
        if ( request()->ajax() ) {
            $data = request()->all();
            //是否上传头像
            if ( $request->head != $user->head ) {
                $upload = base64ToImage($request->head, 'user_head');
                $data[ 'head' ] = $upload[ 'path' ];

                //更新头像session
                session(['head_pic'=>$data[ 'head' ]]);
                //删除头像base64位缓存，以便下次重新转换
                Cache::forget('user_head' . $user->openid);
            }

            //是否上传个人二维码
            if ( $request->qrcode != $user->qrcode ) {
                $upload = base64ToImage($request->qrcode, 'user_qrcode');
                $data[ 'qrcode' ] = $upload[ 'path' ];
            }

            //如果有变更名称或头像则需清空推广图片
            if($request->head != $user->head || $request->wc_nickname != $user->wc_nickname) {
                $data['extension_image'] = '';
            }

            if ( $user->update($data) ) {
                return response()->json([ 'code' => 0, 'errormsg' => '修改资料完成', 'url' => route('index.user') ]);
            } else {
                return response()->json([ 'code' => 401, 'errormsg' => '修改资料失败' ]);
            }
        } else {
            $user_id = session('user_id');
            $res = $user->with('brand')->where('id', $user_id)->first();

            return view('index.user_basic', compact('res'));
        }
    }

    /**
     * @title  获取个人二维码帮助页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qrcodeHelp()
    {
        return view('index.qrcode_help');
    }

    /**
     * 开通会员页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function openMember($uid = 0)
    {
        if($uid) {
            $user = User::find(session('user_id'));
            $puser = User::select('admin_id', 'admin_type')->where('id', $uid)->first();
            //创建关联关系并关联后台员工id和类型
            if(!$user->extension_id && $user->subscribe == 1 && $user->type == 1 && $user->extension_id != $uid) {
                $user->admin_id = $puser->admin_id;
                $user->admin_type = $puser->admin_type;
                $user->extension_id = $uid;
                $user->extension_at = date('Y-m-d H:i:s', time());
                $user->ex_type = 3;
                $user->save();
            }
        }

        $user = User::where('id', session('user_id'))->select('wc_nickname', 'membership_time')->first();

        //微信分享配置
        $app = new Application(config('wechat'));
        $js = $app->js;

        return view('index.open_member', compact('user', 'js'));
    }

    /**
     * 邀请好友(保存生成的图片并上传至微信之后客服消息发送图片)
     * @param $request
     * @return mixed
     */
    public function invitingFriends( Request $request )
    {
        $uid = session('user_id');
        $user = User::where('id', $uid)->first();
        if ( $user->subscribe == 1 ) {

            dispatch(new extensionImage($user, $request->type, $request->url));

            return response()->json([ 'state' => 0, 'errormsg' => '发送成功' ]);
        } else {
            return response()->json([ 'state' => 401, 'errormsg' => '请先关注公众号' ]);
        }
    }

}