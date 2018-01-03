<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 下午 5:41
 */

namespace App\Http\Controllers\Index;

use App\Classes\Gdimage\Images;
use App\Http\Controllers\TraitFunction\FunctionUser;
use App\Http\Controllers\TraitFunction\Wechat;
use App\Model\Footprint;
use App\Model\User;
use App\Model\UserArticles;
use Illuminate\Http\Request;

class UserController extends CommonController
{
    use FunctionUser, Wechat;

    /**
     * @title  个人中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View become_dealer
     */
    public function index($type = '', $dealer = '')
    {
        $uid = \Session::get('user_id');
        $res = User::where('id', $uid)->first()->toArray();
        //未被员工推广的用户才可以关联
        if($res['admin_id'] == 0 && $res['admin_type'] == 0) {
            //通过招商链接进入（成为经销商并关联招商员工）
            if($type == 'become_dealer') User::where('id', $uid)->update(['type' => 2, 'admin_id' => $dealer,'admin_type' => 1]);
            //通过运营链接进入 (该用户关联运营员工)
            if($type == 'become_extension') User::where('id', $uid)->update(['admin_id' => $dealer,'admin_type' => 2]);
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
            $head = \Session::get('head_pic');
            if(strstr(\Session::get('head_pic'), "wx.qlogo.cn", true) == 'http://') {
                $head = app(User::class)->curl_url($head, 2);
            } else {
                $head = app(User::class)->curl_url(config('app.url').$head, 2);
            }
        }
        return view('index.user_center', compact('res', 'pic', 'head'));
    }

    /**
     * @title  用户基本信息及修改
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
            }

            //是否上传个人二维码
            if ( $request->qrcode != $user->qrcode ) {
//                $upload = thumdImageUpload(200, 200, $request->qrcode, 'user_qrcode');
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
            $user_id = \Session::get('user_id');
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
    public function openMember()
    {
        $shiptime = User::where('id', session()->get('user_id'))->select('membership_time')->first();

        //微信支付配置
        $package = wecahtPackage();

        return view('index.open_member', compact('shiptime', 'package'));
    }

    /**
     * 邀请好友(保存生成的图片并上传至微信之后客服消息发送图片)
     */
    public function invitingFriends( Request $request )
    {
        $uid = session()->get('user_id');
        $user = User::where('id', $uid)->first();
        if ( $user->subscribe == 1 ) {
            $this->optionInviting($user, $request);

            return response()->json([ 'state' => 0, 'errormsg' => '发送成功' ]);
        } else {
            $user_info = $this->ObtainUserInfo($user);

            if($user_info['subscribe'] != 1) {
                return response()->json([ 'state' => 401, 'errormsg' => '请先关注公众号' ]);
            } else {
                User::where('id', $uid)->update(['subscribe' => 1]);
                $this->optionInviting($user, $request);
                return response()->json([ 'state' => 0, 'errormsg' => '发送成功' ]);
            }
        }
    }

}