<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 下午 5:41
 */

namespace App\Http\Controllers\Index;

use App\Classes\Gdimage\Images;
use App\Model\Footprint;
use App\Model\User;
use App\Model\UserArticles;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    /**
     * @title  个人中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View become_dealer
     */
    public function index($type = '', $dealer = '', $admintype = '')
    {
        $uid = \Session::get('user_id');;
        if ( $uid ) {
            //查看是否有新访客
            if ( Footprint::where([ 'uid' => $uid, 'new' => 1 ])->first() ) {
                session()->put('newkf', 1);
            } else {
                session()->forget('newkf');
            }

            $res = User::where('id', $uid)->first()->toArray();
            //未被员工推广的用户才可以关联
            if($res['admin_id'] == 0 && $res['admin_type'] == 0) {
                //通过招商链接进入（成为经销商并关联招商员工）
                if($type == 'become_dealer') User::where('id', $uid)->update(['type' => 2, 'admin_id' => $dealer,'admin_type' => $admintype]);
                //通过运营链接进入 (该用户关联运营员工)
                if($type == 'become_extension') User::where('id', $uid)->update(['admin_id' => $dealer,'admin_type' => $admintype]);
            }

            $res[ 'user_article' ] = UserArticles::where('uid', $res[ 'id' ])->count();
            $res[ 'read_share' ] = Footprint::where('uid', $res[ 'id' ])->count();
            $pic = '';
            $head = '';
            if ( $res[ 'extension_image' ] == '' ) {
                $url = app(User::class)->createQrcode($uid);
                //二维码转base64位
                $arr = getimagesize($url);
                $pic = "data:{$arr['mime']};base64," . base64_encode(file_get_contents($url));
                //头像转base64
                if(strpos(\Session::get('head_pic'),"https")) {
                    $head = app(User::class)->curl_url(\Session::get('head_pic'), 2);
                } else {
                    $head = app(User::class)->curl_url(config('app.url').\Session::get('head_pic'), 2);
                }
            }
            return view('index.user_center', compact('res', 'pic', 'head'));
        }
    }

    /**
     * @title  用户基本信息及修改
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userBasic(Request $request, User $user)
    {
        if ( request()->ajax() ) {
            $data = request()->except('_token');
            //是否上传头像
            if ( $request->head ) {
//                $upload = $this->thumdImageUpload(200, 200, request()->file('head'), 'user_head');
                $upload = base64ToImage($request->head, 'user_head');
                $data[ 'head' ] = $upload[ 'path' ];

            }
            //是否上传个人二维码
            if ( $request->qrcode ) {
//                $upload = thumdImageUpload(200, 200, $request->qrcode, 'user_qrcode');
                $upload = base64ToImage($request->qrcode, 'user_qrcode');
                $data[ 'qrcode' ] = $upload[ 'path' ];
            }
            if ( $user->where('id', session()->get('user_id'))->update($data) ) {
                return response()->json([ 'code' => 0, 'errormsg' => '修改资料完成', 'url' => route('index.user') ]);
            } else {
                return response()->json([ 'code' => 401, 'errormsg' => '修改资料失败' ]);
            }
        } else {
            $res = $user->with('brand')->where('id', session()->get('user_id'))->first();

            return view('index.user_basic', compact('res'));
        }
    }

    public function thumdImage( $with, $hight, $imagepath )
    {
        $thumdimage = Images::open($imagepath);
        $thumdimage->thumb($with, $hight);
        $thumdimage->save($imagepath);
    }

    /**
     * @title  获取个人二维码帮助页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function qrcodeHelp()
    {
        return view('index.qrcode_help');
    }

    public function openMember()
    {
        $shiptime = User::where('id', session()->get('user_id'))->select('membership_time')->first();
        //微信分享配置
        $package = wecahtPackage();

        return view('index.open_member', compact('shiptime', 'package'));
    }

    /**
     * 邀请好友(保存生成的图片并上传至微信之后客服消息发送图片)
     */
    public function invitingFriends( Request $request )
    {
        $uid = session()->get('user_id');
        if ( $request->type == 1 ) {
            $image = $request->url;
        } elseif ( $request->type == 2 ) {
            // 保存本地图片
            $path = base64Toimg($request->url, 'inviting_qrcode');
            //待扩展-》如已生成的图片下次直接用原来的图片上传临时素材发送客服消息
            User::where('id', $uid)->update([ 'extension_image' => $path[ 'path' ] ]);
            $image = $path[ 'path' ];
        }
        // 上传临时图片素材
        $app = new Application(config('wechat.wechat_config'));
        $temporary = $app->material_temporary;
        $ret = $temporary->uploadImage("../public_html/uploads/" . $image);
        // 发送客服消息
        $user = User::where('id', $uid)->first();
        if ( $user->subscribe == 1 ) {
            //推送文本消息
            $this->extension_tyep($user->extension_type, $user->extension_num, $user->openid);
            //推送推广图片
            message($user->openid, 'image', $ret->media_id);

            return response()->json([ 'state' => 0, 'errormsg' => '发送成功' ]);
        } else {
            return response()->json([ 'state' => 401, 'errormsg' => '请先关注公众号' ]);
        }
    }

    /**
     * 推送自己的推广状态
     * @param $type
     * @param $openid
     */
    public function extension_tyep( $type, $num, $openid )
    {
        switch ( $type ) {
            case '0':
                $num = 5 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，首次成功邀请5个好友使用可免费赠送5天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";

                return message($openid, 'text', $context);
                break;
            case '1':
                $num = 15 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请10个好友使用可免费赠送5天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '2':
                $num = 35 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请20个好友使用可免费赠送10天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '3':
                $num = 65 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请30个好友使用可免费赠送10天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '4':
                $num = 105 - $num;
                $context = "分享下图邀请你的朋友同事也来使用事业头条，成功邀请40个好友使用可免费赠送20天【谁查看我】功能，您还差 $num 人即可免费享受该功能。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
            case '5':
                $context = "分享下图邀请你的朋友同事也来使用事业头条吧。\n\n↓↓↓↓↓↓";
                message($openid, 'text', $context);
                break;
        }
    }




}