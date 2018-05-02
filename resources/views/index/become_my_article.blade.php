<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>爆单头条</title>
	<link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
	<link rel="stylesheet" href="/index/css/icon.css">
	<link rel="stylesheet" href="/index/css/index.css">
</head>
<body>
<div id="blasting" class="flexv warp">

	<div class="flexv center bottom">
		<i class="flex center bls bls-bottom"></i>
		<div class="qrcode" style="margin-top: 2rem;">
			<img src="{{ $imgurl }}" class="fitimg">
		</div>
		<p>立刻长按二维码</p>
		<p>在文章中嵌入我的名片</p>
		{{--<div class="flex center">&copy;&ensp;2017&ensp;公众号名&ensp;版权所有</div>--}}
	</div>
	<div class="img">
		<img src="/index/image/public.png" class="fitimg">
	</div>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
	@if(strstr($user->head,'http'))
    var head = "{{ $user->head }}";
	@else
    var head = 'http://bw.eyooh.com{{ $user->head }}';
	@endif
    wx.config(<?php echo $js->config(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) ?>);
    wx.ready(function(){
        //分享微信好友
        wx.onMenuShareAppMessage({
            title: '我正在使用事业爆文，精准获取人脉资源，团队增员非常好用！邀请你一起使用！', // 分享标题1
            desc: "团队增员，人脉无忧！{{ $user->wc_nickname }}与您真诚分享。", // 分享描述
            link: "{{ route('become_my_article', [request()->article_id, request()->pid]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head, // 分享图标
            success: function () {}
        });

        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '我正在使用事业爆文，精准获取人脉资源，团队增员非常好用！邀请你一起使用！', // 分享标题
            link: "{{ route('become_my_article', [request()->article_id, request()->pid]) }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head, // 分享图标
            success: function () {}
        });
    });
</script>

</html>