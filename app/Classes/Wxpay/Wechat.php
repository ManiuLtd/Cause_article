<?php
namespace Wxpay;
/**
* 微信支付类
*/
class Wechat
{
    private static $_appId;
    private static $_appSecret;
    private static $_key;
    private static $_mchId;
    private static $_sslCertPath;
    private static $_sslKeyPath;
    private static $_params = array();
    const UNIFIEDORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    const REFUND_URL = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
    const PACKET_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

	public static function setAppId($appId){
		self::$_appId = $appId;
	}

	public static function getAppId(){
		if(!self::$_appId)throw new WechatException('appid is undefined');
		return self::$_appId;
	}

	public static function setAppSecret($appSecret){
		self::$_appSecret = $appSecret;
	}

    private static function getAppSecret(){
		if(!self::$_appSecret)throw new WechatException('appsecret is undefined');
		return self::$_appSecret;
	}

	public static function setKey($key){
		self::$_key = $key;
	}

    private static function getKey(){
		if(!self::$_key)throw new WechatException('key is undefined');
		return self::$_key;
	}

	public static function setMchId($mchId){
		self::$_mchId = $mchId;
	}

    private static function getMchId(){
		if(!self::$_mchId)throw new WechatException('mchid is undefined');
		return self::$_mchId;
	}

	public static function setSslCertPath($sslCertPath){
		self::$_sslCertPath = $sslCertPath;
	}

    private static function getSslCertPath(){
		if(!self::$_sslCertPath)throw new WechatException('sslcertpath is undefined');
		return self::$_sslCertPath;
	}

	public static function setSslKeyPath($sslKeyPath){
		self::$_sslKeyPath = $sslKeyPath;
	}

    private static function getSslKeyPath(){
		if(!self::$_sslKeyPath)throw new WechatException('sslkeypath is undefined');
		return self::$_sslKeyPath;
	}

	public static function httpGet($url){
    	$ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res =  curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public static function httpPost($url,&$postFields){

    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }


    public static function httpPostSSL($url,$xml){
    	$ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch,CURLOPT_HEADER,FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, self::getSslCertPath());
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, self::getSslKeyPath());
        //post提交方式
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }

    public static function xmlToArray($xml){
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    public static function getSign($params,$urlencode=false){

    	
        ksort($params);

        foreach($params as $key=>$v){
            if($urlencode)$v = urlencode($v);
            $str[] = $key.'='.$v;
        }
        $str = implode('&',$str);
        $str .= '&key='.self::getKey();
        return strtoupper(md5($str));
    }

    public static function setParam($key,$value){
        self::$_params[$key] = $value;
    }

    public static function createXml(){
    	self::setParam('sign',self::getSign(self::$_params));
    	$xml =  self::arrayToXml(self::$_params);
    	self::$_params = array();
    	return $xml;
    }

    public static function createNoncestr(){
    	
        return md5(self::$_appId.time());
    }

    //生成退款订单号
    public static function createRefundNo(){
        return time().date('YmdHis').rand(1000000000,9999999999);
    }

    public static function createOrderNo()
    {
        return date('YmdHis') . rand(10000000, 99999999);
    }

    //生成授权url
    public static function createOauthUrl($redirectUrl,$state='',$scope='snsapi_base'){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::getAppId()."&redirect_uri=".urlencode($redirectUrl)."&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
        return $url;
    }


	//微信授权
    public static function oauth($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::getAppId()."&secret=".self::getAppSecret()."&code=".$code."&grant_type=authorization_code";

        $output = self::httpGet($url);

        $output =  json_decode($output,true);

        if(isset($output['errcode']))throw new WechatException('wechat auth error:'.$output['errmsg'].$output['errcode'],$output['errcode']);
        
        return $output;
    }

    //获得访问令牌
    public static function getAccessToken(){
    	$tokenFile = __DIR__.'/accessToken.json';
    	$token = json_decode(file_get_contents($tokenFile));
        $time = time();
    	if($token) {
            if ($token->expires_in > $time) return $token->access_token;
        }
    	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::getAppId()."&secret=".self::getAppSecret();

       	$output = self::httpGet($url);

        $output = json_decode($output);

        if(isset($output->errcode))throw new WechatException('access_token error:'.$output->errmsg);
        
        $output->expires_in += $time;
        file_put_contents($tokenFile,json_encode($output));

        return $output->access_token;
    }

    /**
     * 获得访问令牌二
     * @return mixed
     */
    public static function getToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::getAppId()."&secret=".self::getAppSecret();
        $output = self::httpGet($url);
        return json_decode($output,true);
    }

    /**
     * @获取带参数的公众号二维码
     * @return mixed
     */
    public static function getQRcode($json)
    {
        //获取token
        $token = self::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token['access_token'];
        //获取ticket
        $code = json_decode(self::httpPost($url,$json),true);
        $ticket = $code['ticket'];
        //返回ticket换取二维码
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
    }

    /**
     * @title 创建会员卡券
     * @param $data     post提交的json数据
     * @return mixed
     */
    public static function createCard($data)
    {
        $token  = self::getToken();
        $url    = "https://api.weixin.qq.com/card/create?access_token=".$token['access_token'];
        $card = self::httpPost($url,$data);
        return $card;
    }

    /**
     * @title 删除会员卡券
     * @param $data     post提交的json数据
     * @return mixed
     */
    public static function delCard($data)
    {
        $token  = self::getToken();
        $url    = "https://api.weixin.qq.com/card/delete?access_token=".$token['access_token'];
        $card = self::httpPost($url,$data);
        return $card;
    }

    /**
     * @title   获得授权微信的信息
     * @param $token
     * @param $openId
     * @return mixed
     * @throws WechatException
     */
    public static function getUserInfo($token,$openId){
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openId.'&lang=zh_CN';
        $res = self::httpGet($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']))throw new WechatException('userinfo error:'.$res['errmsg']);
        return $res;
    }

    //获取jsapi令牌
    public static function getJsApiTicket() {
    	$ticketFile = __DIR__.'/jsapiTicket.json';
    	$ticket = json_decode(file_get_contents($ticketFile));
        $time = time();
        if($ticket) {
            if ($ticket->expires_in > $time) return $ticket->ticket;
        }

    	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".self::getAccessToken();
    	$output = self::httpGet($url);

    	$output = json_decode($output);

        if($output->errcode!=0)throw new WechatException('jsapi_ticket error:'.$output->errmsg);
        
        $output->expires_in += $time;
        file_put_contents($ticketFile,json_encode($output));

        return $output->ticket;

    }

    //获取分享package
    public static function getSignPackage() {
        $jsapiTicket = self::getJsApiTicket();
        $timestamp = time();
        $nonceStr = self::createNoncestr();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => self::getAppId(),
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    //模版消息
    public static function notify($openId,$templateId,$postDatas,$detailUrl='',$color='#206EF7')
    {
        $postFields = array(
            'touser' => $openId,
            'template_id' => $templateId,
            'url' => $detailUrl,
            'topcolor' => $color,
            'data' => $postDatas
        );



        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . self::getAccessToken();

        $res = self::httpPost($url,json_encode($postFields));
        
        $res = json_decode($res,true);

        if($res['errcode']!=0)throw new WechatException('notify error:'.$res['errmsg']);

        return $res;
        
    }

    //统一下单
    public static function unifiedorder(){
        self::setParam('appid',self::getAppId());
        self::setParam('mch_id',self::getMchId());
        self::setParam('nonce_str',self::createNoncestr());
        self::setParam('spbill_create_ip',$_SERVER['REMOTE_ADDR']);
        $xml = self::createXml();

       	$res = self::httpPost(self::UNIFIEDORDER_URL,$xml);
        
        $res = self::xmlToArray($res);

       	if($res['return_code']=='FAIL')throw new WechatException('unifiedorder return error:'.$res['return_msg']);
       	if($res['result_code']=='FAIL')throw new WechatException('unifiedorder result error:'.$res['err_code_des']);

       	return $res;

    }

    public static function getJsApiParams($prepayId){
    	$json = array(
    		'appId'		=>	self::getAppId(),
    		'timeStamp'	=>	time().'',
    		'nonceStr'	=>	self::createNoncestr(),
    		'package'	=>	"prepay_id={$prepayId}",
    		'signType'	=>	'MD5',
    	);
    	$json['paySign'] = self::getSign($json);
    	return json_encode($json);
    }


    public static function goPay($prepayId,$callbackUrl,$khzfUrl){
        $jsapiParam = self::getJsApiParams($prepayId);
        $str=<<<EOF
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>微信安全支付</title>
    <style>
    .tips{color:red;font-weight:bold;}
    </style>
</head>
<body>
<div id="qrcode">
</div>
<script type="text/javascript">

        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                %s,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    if(res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:cancel" ){
                        location='%s' + '?code=' + res.err_msg;
                    }else{
                        var qrcode = document.getElementById('qrcode');
                        qrcode.innerHTML='<p class="tips">跨号支付？亲别急，请按以下步骤进行操作：</p><p>1、首先长按二维码图片将图片保存到手机</p><p>2、再通过微信扫一扫功能，点击右上角相册扫描刚刚保存的二维码图片</p><p>3、进入支付页面继续完成支付</p><p style="text-align:center"><img src="http://s.jiathis.com/qrcode.php?url=%s"></p>';
                    }
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
        callpay();
    </script>
</body>
</html>
EOF;
        printf($str,$jsapiParam,$callbackUrl,$khzfUrl);
    }



    public static function refund(){
       	self::setParam('appid',self::getAppId());
        self::setParam('mch_id',self::getMchId());
        self::setParam('op_user_id',self::getMchId());
        self::setParam('nonce_str',self::createNoncestr());
        self::setParam('out_refund_no',self::createRefundNo());
        $xml = self::createXml();
        $res = self::httpPostSSL(self::REFUND_URL,$xml);
        $res = self::xmlToArray($res);

        if($res['return_code']=='FAIL')throw new WechatException('refund return error:'.$res['return_msg']);
       	if($res['result_code']=='FAIL')throw new WechatException('refund result error:'.$res['err_code_des']);
        
        return $res;
    }

    /**
     * @title       发送红包唯一订单号（需要自己生成，不能重复使用）
     * @return string
     */
    private static function createBillNo(){
        return self::getMchId().date("YmdHis").rand(1000,9999);
    }

    /**
     * @title                   公众号发送红包
     * @param $openId           包接收者Openid
     * @param $fee              红包金额（如1元填写100，最少不能少于1元）
     * @param $remark           活动红包备注信息
     * @param $sendName         商户名称（红包上会显示的哦）
     * @param $actName          活动名称
     * @param string $wishing   红包的祝福语
     * @return mixed
     * @throws WechatException
     */
    public function sendPacket($openId,$fee,$remark,$sendName,$actName,$wishing='感谢您的支持'){
        $billNo = self::createBillNo();
        self::setParam('mch_billno',$billNo);
        self::setParam('re_openid',$openId);
        self::setParam('wishing',$wishing);
        self::setParam('total_amount',$fee*100);
        self::setParam('act_name',$actName);
        self::setParam('remark',$remark);
        self::setParam('send_name',$sendName);
        self::setParam('nonce_str',self::createNoncestr());
        self::setParam('mch_id',self::getMchId());
        self::setParam('wxappid',self::getAppId());
        self::setParam('total_num',1);
        self::setParam('client_ip',get_client_ip());
        $xml = self::createXml();
        $res = self::httpPostSSL(self::PACKET_URL,$xml);
        $res = self::xmlToArray($res);

        if($res['return_code']=='FAIL')throw new WechatException('sendpacket return error:'.$res['return_msg']);
        if($res['result_code']=='FAIL')throw new WechatException('sendpacket result error:'.$res['err_code_des']);

        return $res;
    }

    //上传媒体文件
    public static function upload($mediaUrl,$mediaType='image'){
       
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".self::getAccessToken()."&type={$mediaType}";

        $fileData = array('file1'=>'@'.$mediaUrl);
        $res = self::httpPost($url,$fileData);
        $res = json_decode($res,true);

        if(isset($res['errcode']))throw new WechatException('upload error:'.$res['errmsg']);
        
        return $res;
    }


    public static function getMediaId($mediaUrl,$mediaType='image'){
    	$res = self::upload($mediaUrl,$mediaType);
    	return $res['media_id'];
    }

    public static function createQrcode($sceneStr){
        $token = self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
        $fields = array(
            'action_name'   =>'QR_LIMIT_STR_SCENE',
            'action_info'   =>array('scene'=>array('scene_str'=>$sceneStr))
        );
        $res = self::httpPost($url,json_encode($fields));
        $res = json_decode($res,true);

        if(isset($res['errcode']))throw new WechatException('create qrcode error:'.$res['errmsg']);
        
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($res['ticket']);
    }

    public static function reponseText($source,$openId,$text){
        $msg = array(
            'ToUserName'    =>$openId,
            'FromUserName'  =>$source,
            'CreateTime'    =>time(),
            'MsgType'       =>'text',
            'Content'       =>$text
        );
        return self::arrayToXml($msg);
    }

    //响应图片消息
    public static function responseImg($source,$openId,$mediaId){
        $msg = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>';

        return sprintf($msg,$openId,$source,time(),$mediaId);
    }



}