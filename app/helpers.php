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
    $nowUrl = $url ? $url : ucfirst(\think\Request::instance()->controller()).'/'. strtolower(\think\Request::instance()->action());
    $allowList = array('index/index','index/profile','index/upload','index/trash','index/ueditor');
    if(in_array($nowUrl,$allowList)) return true;
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
 * @title 推广用户成功奖励逻辑
 * @param $id
 */
function extension($id)
{
    User::where('id',$id)->increment('extension_num', 1);
    $user = User::find($id);
    if($user->extension_num <= 105) {
        switch ($user->extension_num) {
            case '5':
                if ($user->extension_type == 0) {
                    membership_time($user->membership_time, $user->id, 5, 1);
                    $context = "恭喜您成功推荐5位朋友使用事业头条，你已获赠5天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐10位好友使用，获赠更多功能特权！";
                    message($user->openid, 'text', $context);
                }
                break;
            case '15':
                if ($user->extension_type == 1) {
                    membership_time($user->membership_time, $user->id, 5, 2);
                    $context = "恭喜您成功推荐10位朋友使用事业头条，你已获赠5天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐20位好友使用，获赠更多功能特权！";
                    message($user->openid, 'text', $context);
                }
                break;
            case '35':
                if ($user->extension_type == 2) {
                    membership_time($user->membership_time, $user->id, 10, 3);
                    $context = "恭喜您成功推荐20位朋友使用事业头条，你已获赠10天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐30位好友使用，获赠更多功能特权！";
                    message($user->openid, 'text', $context);
                }
                break;
            case '65':
                if ($user->extension_type == 3) {
                    membership_time($user->membership_time, $user->id, 10, 4);
                    $context = "恭喜您成功推荐30位朋友使用事业头条，你已获赠10天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐40位好友使用，获赠更多功能特权！";
                    message($user->openid, 'text', $context);
                }
                break;
            case '105':
                //推广奖励到此就获取完
                if ($user->extension_type == 4) {
                    membership_time($user->membership_time, $user->id, 20, 5);
                    $context = "恭喜您成功推荐40位朋友使用事业头条，你已获赠20天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n事业爆文感谢你的支持，祝你使用愉快！";
                    message($user->openid, 'text', $context);
                }
                break;
        }
    }
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
    $app = new Application(config('wechat.wechat_config'));
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
    $staff->message($message)->to($FromUserName)->send();
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
 * @title 操作会员时间
 * @param $membership_time
 * @param $user_id
 * @param $daynum
 * @param $tyep
 */
function membership_time($membership_time, $user_id, $daynum, $tyep){
    if (Carbon::parse('now')->gt(Carbon::parse($membership_time))) {
        User::where('id',$user_id)->update(['membership_time' => Carbon::now()->addDays($daynum), 'extension_type' => $tyep]);
    } else {
        User::where('id',$user_id)->update(['membership_time'=>Carbon::parse($membership_time)->addDays($daynum),'extension_type'=>$tyep]);
    }
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
function remove_duplicate($array){
    $result=array();
    foreach ($array as $key => $value) {
        $has = false;
          foreach($result as $val){
              if($val['see_uid'] == $value['see_uid']){
                $has = true;
                break;
              }
          }
        if(!$has){
            $result[]=$value;
        }
    }
    return $result;
}

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
        $new_file = "../public_html/uploads/$file_name/$daytime/";
        if(!file_exists($new_file))
        {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777);
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

