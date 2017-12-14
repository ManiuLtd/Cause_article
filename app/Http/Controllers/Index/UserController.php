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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $uid = session()->get('user_id');
        if ( $uid ) {
            //查看是否有新访客
            if ( Footprint::where([ 'uid' => $uid, 'new' => 1 ])->first() ) {
                session()->put('newkf', 1);
            } else {
                session()->forget('newkf');
            }

            $res = User::where('id', $uid)->first()->toArray();
            $res[ 'user_article' ] = UserArticles::where('uid', $res[ 'id' ])->count();
            $res[ 'read_share' ] = Footprint::where('uid', $res[ 'id' ])->count();
            $pic = '';
            $head = '';
            if ( $res[ 'extension_image' ] == '' ) {
                //创建永久二维码
                $options = config('wechat.wechat_config');
                $app = new Application($options);
                $qrcode = $app->qrcode;
                $result = $qrcode->forever($uid);// 或者 $qrcode->forever("foo");
                $ticket = $result->ticket; // 或者 $result['ticket']
                $url = $qrcode->url($ticket);
                //二维码转base64位
                $arr = getimagesize($url);
                $pic = "data:{$arr['mime']};base64," . base64_encode(file_get_contents($url));
                //头像转base64
                $head = $this->curl_url(session()->get('head_pic'),2);
            }

            return view('index.user_center', compact('res', 'pic', 'head'));
        }
    }

    //微信头像转base64
    public function curl_url($url,$type=0,$timeout=30){

        $msg = ['code'=>2100,'status'=>'error','msg'=>'未知错误！'];
        $imgs= ['image/jpeg'=>'jpeg', 'image/jpg'=>'jpg', 'image/gif'=>'gif', 'image/png'=>'png', 'text/html'=>'html', 'text/plain'=>'txt', 'image/pjpeg'=>'jpg', 'image/x-png'=>'png', 'image/x-icon'=>'ico' ];
        if(!stristr($url,'http')){
            $msg['code']= 2101;
            $msg['msg'] = 'url地址不正确!';
            return $msg;
        }
        $dir= pathinfo($url);
        $host = $dir['dirname'];
        $refer= $host.'/';
        $ch = curl_init($url);
        curl_setopt ($ch, CURLOPT_REFERER, $refer); //伪造来源地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回变量内容还是直接输出字符串,0输出,1返回内容
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);//在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_HEADER, 0); //是否输出HEADER头信息 0否1是
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); //超时时间
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $httpCode = intval($info['http_code']);
        $httpContentType = $info['content_type'];
        $httpSizeDownload= intval($info['size_download']);

        if($httpCode!='200'){
            $msg['code']= 2102;
            $msg['msg'] = 'url返回内容不正确！';
            return $msg;
        }
        if($type>0 && !isset($imgs[$httpContentType])){
            $msg['code']= 2103;
            $msg['msg'] = 'url资源类型未知！';
            return $msg;
        }
        if($httpSizeDownload<1){
            $msg['code']= 2104;
            $msg['msg'] = '内容大小不正确！';
            return $msg;
        }
        if($type==0 or $httpContentType=='text/html') $msg['data'] = $data;
        $base_64 = base64_encode($data);
        if($type==1) $msg['data'] = $base_64;
        elseif($type==2) $msg['data'] = "data:{$httpContentType};base64,{$base_64}";
        unset($info,$data,$base_64);
        return $msg['data'];

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