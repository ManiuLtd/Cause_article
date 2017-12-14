<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * 生成验证码
     * @param $tmp 刷新验证码
     */
    public function captcha($tmp)
    {
        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 40, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();
        //把内容存入session
        Session::flash('milkcaptcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    /**
     * 显示后台登录模板
     */
    public function login(Request $request)
    {
        if($request->post()) {
            if (Session::get('milkcaptcha') == $request->code) {
                //用户输入验证码正确
                $check = Auth::attempt(['account'=>$request->account, 'password'=>$request->password]);
                if ($check) {
                    //验证用户正确
                    return json_encode(['state'=>0, 'msg'=>'登录成功', 'url'=>route('admin')]);
                }
            } else {
                //用户输入验证码错误
                return json_encode(['state'=>401, 'msg'=>'验证码错误']);
            }
        }else {
            return view('admin.login.login');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login');
    }

}