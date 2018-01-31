<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 上午 11:57
 */

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Model\Photo;
use App\Model\PhotoType;
use App\Model\User;
use Illuminate\Support\Facades\Cache;

class PhotoController extends Controller
{
    public function index($tyep = '')
    {
        $type = PhotoType::orderBy('sort', 'desc')->get();
        if($type) {
            $photos = Photo::where('type_id', $tyep)->get();
        } else {
            foreach ( $type as $value ) {
                $photos = Photo::where('type_id', $value->id)->get();
                continue;
            }
        }

        return view('index.extension_photo_list', compact('type', 'photos'));
    }

    public function poster(Photo $photo)
    {
        $pic = Cache::remember('user_qrcode', 60 * 24 * 29, function () {
            $url = app(User::class)->createQrcode(session('user_id'));
            //二维码转base64位
            $pic = "data:image/jpeg;base64," . base64_encode(file_get_contents($url));

            return $pic;
        });

        $head = Cache::remember('user_head', 60 * 24 * 30, function () {
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

        return view('index.extension_poster', compact('photo', 'pic', 'head'));
    }
}