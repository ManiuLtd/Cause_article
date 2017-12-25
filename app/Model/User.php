<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Foundation\Application;

class User extends Model
{
    protected $guarded = ['membership_time', 'extension_num', 'extension_type', '_token', '_method'];

    /**
     * @title  所属品牌
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    /**
     * 所属后台员工
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * @title  推广人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function extension()
    {
        return $this->belongsTo(User::class,'extension_id');
    }

    /**
     * @title  经销商
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id');
    }

    /**
     * @title  我的文章
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_article()
    {
        return $this->hasMany(UserArticles::class,'uid');
    }

    /**
     * 生成微信二维码
     * @param $uid
     * @return string
     */
    public function createQrcode($uid)
    {
        //创建永久二维码
        $options = config('wechat.wechat_config');
        $app = new Application($options);
        $qrcode = $app->qrcode;
        $result = $qrcode->forever($uid);// 或者 $qrcode->forever("foo");
        $ticket = $result->ticket; // 或者 $result['ticket']
        return $qrcode->url($ticket);
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
}
