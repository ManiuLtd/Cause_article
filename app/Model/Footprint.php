<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/10 0010
 * Time: 下午 4:24
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Footprint extends Model
{
    protected $guarded = ['_token','_method'];

    public function userArticle()
    {
        return $this->belongsTo(UserArticles::class, 'uaid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'see_uid');
    }

    public function extension_user($footprint, $result = [], $deep = 0)
    {
        $deep += 1;
        $et_user = Footprint::with('user')->where(['see_uid' => $footprint->ex_id, 'uaid' => $footprint->uaid])->first();
        if(isset($et_user->ex_id)) {
            if($et_user->ex_id != $et_user->uid) {
                $result[$deep] = $et_user;
                $this->extension_user($et_user, $result, $deep);
            }
        }
        dd($result);

    }
}