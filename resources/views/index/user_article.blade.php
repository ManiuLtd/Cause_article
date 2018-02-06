<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>我的头条</title>
	@include('index.public.css')
</head>
<body>
<div id="headlines" class="flexv wrap">
	<div class="flexitemv mainbox">
		<div class="listbox">
			@foreach($list as $value)
				<a href="{{route('user_article_details',['id'=>$value->id])}}" class="flex">
					<div class="flex lists">
						<div class="img">
							<img class="fitimg" src="{{$value->article['pic']}}"/>
						</div>
						<div class="flexitemv cont">
							<h2 class="flexv">{{$value->article['title']}}</h2>
							<div class="base">
								<span><em>{{$value->read}}</em>阅读</span>
								<span><em>{{$value->share}}</em>分享</span>
								<span><em>{{$value->created_at->diffForHumans()}}</em></span>
							</div>
						</div>
					</div>
				</a>
			@endforeach
		</div>
		<p class="flex center more">没有更多了~</p>
	</div>
</div>
</body>

<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{ $package['appId'] }}', // 必填，公众号的唯一标识
        timestamp: {{ $package['timestamp'] }}, // 必填，生成签名的时间戳
        nonceStr: '{{ $package['nonceStr'] }}', // 必填，生成签名的随机串
        signature: '{{ $package['signature' ]}}',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

	@if(strstr(session('head_pic'),'http'))
    	var head = "{{ session('head_pic') }}";
	@else
    	var head = 'http://bw.eyooh.com{{ session('head_pic') }}';
	@endif
    wx.ready(function(){
        //分享微信好友
        wx.onMenuShareAppMessage({
            title: '{{ session('nickname') }}的头条', // 分享标题1
            desc: '请点开我的专属头条，超多精彩内容，尽在事业爆文！', // 分享描述
            link: '{{ route("user_article", session("user_id")) }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head // 分享图标
        });

        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '{{ session('nickname') }}的头条', // 分享标题
            link: '{{ route("user_article", session("user_id")) }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head // 分享图标
        });
    });
</script>

</html>