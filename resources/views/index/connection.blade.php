<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>人脉分析</title>
    @include('index.public.css')
</head>
<body>
<div id="contact" class="flexv wrap">
    <div class="flexv mainbox">
        <div class="flexv center sub">
            <div class="userimg">
                <img src="{{$res[0]['user']['head']}}" class="fitimg">
            </div>
            <h2 class="flex center name">{{$res[0]['user']['wc_nickname']}}</h2>
            <p class="several">共阅读你的头条<em class="color">{{$read}}</em>次，分享<span class="color">{{$share}}</span>次</p>
            <p class="time">最近访问<em class="color">{{\Carbon\Carbon::parse($res[0]['created_at'])->diffForHumans()}}</em>。</p>
        </div>
    </div>
    <div class="flexitemv center">
        <div class="flexv center text">
            <i class="flex center bls bls-bottom"></i>
            <p>根据事业头条人脉分析，</p>
            <p>此客户可能是你的好友或群友。</p>
        </div>
        <div class="flex center relation">
            <div class="img">
                <img src="{{session()->get('head_pic')}}" class="fitimg">
            </div>
            <i class="flex center bls bls-xianlu"></i>
            <div class="img">
                <img src="{{$res[0]['user']['head']}}" class="fitimg">
            </div>
        </div>
    </div>
</div>
</body>
</html>