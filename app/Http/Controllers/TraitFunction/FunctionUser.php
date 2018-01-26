<?php

namespace App\Http\Controllers\TraitFunction;


use App\Model\User;

trait FunctionUser
{
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

    /**
     * @title 推广用户成功奖励
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
                        $this->membership_time($user->membership_time, $user->id, 5, 1);
                        $context = "恭喜您成功推荐5位朋友使用事业头条，你已获赠5天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐10位好友使用，获赠更多功能特权！";
                        message($user->openid, 'text', $context);
                    }
                    break;
                case '15':
                    if ($user->extension_type == 1) {
                        $this->membership_time($user->membership_time, $user->id, 5, 2);
                        $context = "恭喜您成功推荐10位朋友使用事业头条，你已获赠5天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐20位好友使用，获赠更多功能特权！";
                        message($user->openid, 'text', $context);
                    }
                    break;
                case '35':
                    if ($user->extension_type == 2) {
                        $this->membership_time($user->membership_time, $user->id, 10, 3);
                        $context = "恭喜您成功推荐20位朋友使用事业头条，你已获赠10天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐30位好友使用，获赠更多功能特权！";
                        message($user->openid, 'text', $context);
                    }
                    break;
                case '65':
                    if ($user->extension_type == 3) {
                        $this->membership_time($user->membership_time, $user->id, 10, 4);
                        $context = "恭喜您成功推荐30位朋友使用事业头条，你已获赠10天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n继续推荐40位好友使用，获赠更多功能特权！";
                        message($user->openid, 'text', $context);
                    }
                    break;
                case '105':
                    //推广奖励到此就获取完
                    if ($user->extension_type == 4) {
                        $this->membership_time($user->membership_time, $user->id, 20, 5);
                        $context = "恭喜您成功推荐40位朋友使用事业头条，你已获赠20天免费使用【谁查看我】功能，赶紧点击下方“谁查看我”看看吧！ \n↓↓↓↓↓↓↓↓ \n\n事业爆文感谢你的支持，祝你使用愉快！";
                        message($user->openid, 'text', $context);
                    }
                    break;
            }
        }
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
}