<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 上午 11:57
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Jobs\extensionphoto;
use App\Model\Photo;
use App\Model\PhotoType;
use App\Model\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * 美图列表
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($type = '')
    {
        $types = PhotoType::orderBy('sort', 'desc')->get();
        if($type) {
            $photos = Photo::where('type_id', $type)->get();
        } else {
            foreach ( $types as $value ) {
                $photos = Photo::where('type_id', $value->id)->get();
                break;
            }
        }

        return view('index.extension_photo_list', compact('types', 'photos'));
    }

    /**
     * 推广图
     * @param Photo $photo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function poster(Photo $photo)
    {
        $user = User::with('brand')->where('id', session('user_id'))->first();

        $pic = Cache::remember('user_qrcode'.$user->openid, 60 * 24 * 29, function () {
            $url = app(User::class)->createQrcode(session('user_id'));
            //二维码转base64位
            $pic = "data:image/jpeg;base64," . base64_encode(file_get_contents($url));

            return $pic;
        });

        $head = Cache::remember('user_head'.$user->openid, 60 * 24 * 30, function () {
            //头像转base64
            $head = session('head_pic');
            if(strstr(session('head_pic'), "wx.qlogo.cn", true) == 'http://') {
                $content = file_get_contents($head);
                $head =  'data:image/jpeg;base64,' . base64_encode($content);
            } else {
                $content = file_get_contents(config('app.url').$head);
                $head =  'data:image/jpeg;base64,' . base64_encode($content);
            }
            return $head;
        });

        $rand_photo = $this->randPhoto(3, 1);

        return view('index.extension_poster', compact('user', 'photo', 'pic', 'head', 'rand_photo'));
    }

    /**
     * 随机获取图片
     * @param $count
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function randPhoto( $count, $type )
    {
        $photo = Photo::get()->random($count);
        if($type == 1) {
            return $photo->all();
        } elseif ($type == 2) {
            return response()->json(['photo'=>$photo[0]['url']]);
        } elseif ($type == 3) {
            $view = view('index.rand_photo_list', compact('photo'))->render();
            return response()->json(['view'=>$view]);
        }
    }

    /**
     * 推送海报到公众号
     * @param Request $request
     * @return mixed
     */
    public function photoShare( Request $request )
    {
        $user = User::find(session('user_id'));
        dispatch(new extensionphoto($user, $request->img));

        return response()->json(['state' => '0']);
    }
}