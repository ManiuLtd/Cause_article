<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>首页</title>
	@include('index.public.css')
	<style>
		.mescroll-totop{bottom: 70px !important;}
	</style>
</head>
<body>
<div id="home" class="flexv wrap">
	<div class="flexitemv box">
		<div class="flex center nav">
			{{--<a href="{{route('index.index')}}" class="flex center item @if(request()->type == '') current @endif"><span class="flex center">热文分享</span></a>--}}
			@foreach($article_type as $type)
				<a href="{{ route('index.index',['type'=>$type->id]) }}" class="flex center item @if(request()->type == $type->id) current @endif"><span class="flex center">{{ $type->name }}</span></a>
			@endforeach
			{{--<a href="javascript:;" class="flex center bls bls-yjt more"></a>--}}
		</div>
		<div class="flexitemv mainbox mescroll" id="mescroll">
			{{--<div class="flex banner">--}}
			{{--<div class="swiper-container">--}}
			{{--<div class="swiper-wrapper">--}}
			{{--@foreach($banner_list as $value)--}}
			{{--<div class="swiper-slide"><img class="fitimg" src="/uploads/{{ $value->image }}"/></div>--}}
			{{--@endforeach--}}
			{{--</div>--}}
			{{--<div class="swiper-pagination"></div>--}}
			{{--</div>--}}
			{{--</div>--}}
			<form action="{{route('article_search')}}" method="get" id="search">
				<div class="flex center search">
					<div class="flex centerv home-sea">
						<input type="text" name="key" class="flexitem sea-text" placeholder="输入关键字，找文章">
						<i class="flex smtxt"></i>
						<span class="flex center bls bls-fdj submit"></span>
					</div>
				</div>
			</form>
			<div class="listbox" id="listbox">

			</div>
		</div>
	</div>

	<!--推荐好文章-->
	<a href="{{ route('extension_article') }}" class="flex center renew">提交好文章</a>

	@include('index.public.footer')

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')

</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script src="/index/js/mescroll.min.js"></script>
<script src="/index/js/lazyload.js"></script>
@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')

@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '', 'html' => 'listbox', 'route' => route('index.index', request()->type), 'lists' => $list, 'lazyload' => 1])

<script>
    $(".cuo").hide();

    //给分类第一个标签加上选中状态
	@if(request()->type == '')
    $('.flex.center.item').eq(0).addClass('current');
	@endif

    // new Swiper ('.swiper-container', {
    //     loop: true,
    //     autoplay:1500,
    //     pagination: '.swiper-pagination',
    //     autoplayDisableOnInteraction:false
    // });

    $('.submit').click(function(){
        $('#search').submit();
    });

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public._infomation_js')



    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$package['appId']}}', // 必填，公众号的唯一标识
        timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$package['signature']}}',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        //分享微信好友
        wx.onMenuShareAppMessage({
            title: '欢迎进入事业爆文文章库！', // 分享标题
            desc: '超多精彩爆文，每日更新推送，快来看看吧！', // 分享描述
            link: '{{ config("app.url") }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{ config("app.url") . "logo.jpg" }}' // 分享图标
        });
        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '欢迎进入事业爆文文章库！', // 分享标题
            link: '{{ config("app.url") }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{ config("app.url") . "logo.jpg" }}' // 分享图标
        });
    });
</script>

</html>