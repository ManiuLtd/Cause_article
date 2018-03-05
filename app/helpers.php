<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29 0029
 * Time: 下午 5:37
 */

use App\Model\User;
use Wxpay\Wechat;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;

/**模板显示截取字符串
 * @param $text 字符串
 * @param $length 多少字符后隐藏
 * @return string
 */
function subtext($text, $length)
{
    if(mb_strlen($text, 'utf8') > $length)
        return mb_substr($text, 0, $length, 'utf8').'...';
    return $text;
}

/**
 * 全局变量
 *
 * @param $name 变量名
 * @param string $value 变量值
 * @return mixed 返回值
 */
if (!function_exists('v')) {
    function v($name = null, $value = '[null]')
    {
        static $vars = [];
        if (is_null($name)) {
            return $vars;
        } else if ($value == '[null]') {
            //取变量
            $tmp = $vars;
            foreach (explode('.', $name) as $d) {
                if (isset($tmp[$d])) {
                    $tmp = $tmp[$d];
                } else {
                    return null;
                }
            }
            return $tmp;
        } else {
            //设置
            $tmp = &$vars;
            foreach (explode('.', $name) as $d) {
                if (!isset($tmp[$d])) {
                    $tmp[$d] = [];
                }
                $tmp = &$tmp[$d];
            }
            return $tmp = $value;
        }
    }
}

/**
 * 递归输出
 * @title  后台左侧列表栏目显示
 * @param $items 数组
 * @param string $id
 * @param string $pid 父栏目id
 * @param string $son 子栏目数组
 * @return array
 */
function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
{
    $tree = array();
    $tmpMap = array();

    foreach ($items as $item) {
        $tmpMap[$item[$id]] = $item;
    }
    foreach ($items as $item) {
        if (isset($tmpMap[$item[$pid]])) {
            $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
        } else {
            $tree[] = &$tmpMap[$item[$id]];
        }
    }
    return $tree;
}

/**
 * 检测用户权限
 * @param array $menus 权限列表
 * @param string $url 检测的权限
 * @return boolean
 */
function has_menu ($menus,$url = null){
    $nowUrl = $url;
    $result = false;
    foreach($menus as $k=>$v){
        if($v['url'] == $nowUrl){
            $result = true;
            break;
        }else{
            if(isset($v['children'])) {
                foreach ($v['children'] as $kk => $vv) {
                    if ($vv['url'] == $nowUrl) {
                        $result = true;
                        break;
                    }else{
                        if(isset($vv['children'])) {
                            foreach ($vv['children'] as $kkk => $vvv) {
                                if ($vvv['url'] == $nowUrl) {
                                    $result = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $result;
}

/**
 * @title 推送文本、图片消息
 * @param $app
 * @param $FromUserName
 * @param $type
 * @param $context
 */
function message($FromUserName,$type,$context)
{
    $app = new Application(config('wechat'));
    $staff = $app->staff;
    switch($type) {
        case 'text':
            //推送推广成功消息（客服消息）
            $message = new \EasyWeChat\Message\Text(['content' => $context]);
            break;
        case 'image':
            //推送推广图片消息（客服消息）
            $message = new \EasyWeChat\Message\Image(['media_id' => $context]);
            break;
    }
    try {
        $staff->message($message)->to($FromUserName)->send();
    } catch (Exception $exception) {
        Log::info('发送类型：' . $type. '；错误：' . $exception->getMessage() . '；发送超时有可能是长时间未跟公众号互动');
    }
}

/**
 * @title 推送模板消息
 * @param $app 实例easywechat类
 * @param $FromUserName 用户openid
 * @param array $data 模板内容（数组）
 * @param $template_id 模板id
 * @param $url 跳转链接
 */
function template_message($app,$FromUserName,array $data,$template_id,$url)
{
    //推送模板消息
    $notice = $app->notice;
    $userId = $FromUserName;
    $notice->uses($template_id)->withUrl($url)->andData($data)->andReceiver($userId)->send();
}


/**
 * @title 微信分享配置信息
 * @return array
 */
function wecahtPackage()
{
    Wechat::setAppId(env('WECHAT_APP_ID'));
    Wechat::setAppSecret(env('WECHAT_APP_SECRET'));
    return Wechat::getSignPackage();
}

/**
 * @title 二维数组去重
 * @param $array
 * @return array
 */
//function remove_duplicate($array){
//    $result=array();
//    foreach ($array as $key => $value) {
//        $has = false;
//          foreach($result as $val){
//              if($val['see_uid'] == $value['see_uid']){
//                $has = true;
//                break;
//              }
//          }
//        if(!$has){
//            $result[]=$value;
//        }
//    }
//    return $result;
//}

/**
 * 把base64转为图片保存到本地
 */
function base64Toimg($img_base64,$file_name)
{
    $base64_image_content = $img_base64;
    $daytime = date('Ymd',time());

    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $new_file = config('app.image_real_path')."uploads/$file_name/$daytime/";
        if(!file_exists($new_file))
        {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777, true);
        }
        $imgae_name = time().rand(1000,9999);
        $file = $imgae_name.'.'.$type;
        if (file_put_contents($new_file.$file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            return ['state'=>1, 'path'=>"$file_name/$daytime/$file"];
        }else{
            return ['state'=>0];
        }
    }
}

function GrabImage($url, $filename){
    if(empty($url)){
        return false;
    }
    $ext = '.jpg';
    //目录+文件
    $filename = "/uploads/user_head/".$filename.$ext;
    $dir = "../public_html" . $filename;
    //开始捕捉
    ob_start();
    readfile($url);
    $img = ob_get_contents();
    ob_end_clean();
    strlen($img);
    $fp2 = fopen($dir , "a");
    fwrite($fp2, $img);
    fclose($fp2);
    return $filename;
}

/**
 * 记录发货和开通会员成功后发送短信的信息
 * @param null $phone
 * @param null $status
 * @param string $type
 */
function rwLog($phone = null, $status=null, $type = ''){
    $filename = storage_path('logs/sms/').$type.date("Y-m-d").".log";
    if(file_exists($filename)){
        /*数组写入*/
        $arr = array('phone'=>$phone,'message'=>$status,'time'=>date("Y-m-d H:i:s"));
        file_put_contents($filename, print_r ($arr,true),FILE_APPEND);/*FILE_APPEND:追加文件写入*/
    }else{
        fopen($filename, "w");/*创建log文件*/
        $arr = array('phone'=>$phone,'message'=>$status,'time'=>date("Y-m-d H:i:s"));
        file_put_contents($filename, print_r ($arr,true),FILE_APPEND);/*FILE_APPEND:追加文件写入*/
    }
}

