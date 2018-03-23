<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>首页</title>
	@include('index.public.css')
	<style>
		.loading, .ending{
			font-size: 1.3rem;
			color: #888888;
			padding: 10px 0;
		}
		.hide {display: none;}
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
		<div class="flexitemv mainbox">
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
			<div class="listbox">
				@foreach($list as $value)
					<a href="{{route('article_details',['id'=>$value->id])}}" class="flex lists">
						<div class="img flex center">
							@if(request()->type == 3)
								<img class="lazy" data-original="/index/image/night.jpg" src="/index/image/loading.gif" />
								<i class="flex center bls bls-video"></i>
							@else
								<img class="lazy" data-original="{{$value->pic}}" src="/index/image/loading.gif" />
							@endif
						</div>
						<div class="flexitemv cont">
							<h2 class="flexv">{{$value->title}}</h2>
							<div class="flex base">
								<span class="flex center">
									<i class="flex center bls @if(request()->type == 3) bls-listen @else bls-ck @endif"></i>
									{{$value->read}}
								</span>
								<span class="flex center"><i class="flex center bls bls-time"></i>{{ $value->created_at->toDateString() }}</span>
							</div>
						</div>
					</a>
				@endforeach
			</div>
			<p class="flex center loading hide">正在加载中~</p>
			<p class="flex center ending hide">已全部加载~</p>
		</div>
	</div>

	<!--推荐好文章-->
	<a href="{{ route('extension_article') }}" class="flex center renew">提交好文章</a>

	@include('index.public.footer')

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')
	
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/index/js/lazyload.js"></script>
<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')

<script>
    $(".lazy").lazyload({
        event: "scrollstop",
		effect : "fadeIn",
        container: $(".listbox"),
        load:function ($e) {
            $e.css({"width":"100%","height":"100%"});
        }
    });

    $(".cuo").hide();

    //给分类第一个标签加上选中状态
	@if(request()->type == '')
		$('.flex.center.item').eq(0).addClass('current');
	@endif

    new Swiper ('.swiper-container', {
        loop: true,
        autoplay:1500,
        pagination: '.swiper-pagination',
        autoplayDisableOnInteraction:false
    });

	$('.submit').click(function(){
	    $('#search').submit();
	});

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public._infomation_js')

    // 简单的防抖动函数
    function debounce(func, wait) {
        // 定时器变量
        var timeout;
        return function() {
            // 每次触发 scroll handler 时先清除定时器
            clearTimeout(timeout);
            // 指定 xx ms 后触发真正想进行的操作 handler
            timeout = setTimeout(func, wait);
        };
    };
    // 实际想绑定在 scroll 事件上的 handler
    function realFunc(){
		var scrollTop = Math.ceil(scroll.scrollTop()),thisHeight = scroll.height(),boxHeight = $(".listbox").height();
		console.log(scrollTop,thisHeight,boxHeight);
		if((scrollTop + thisHeight) > boxHeight - 10) {
			page++;
			if(page < {{ $list->lastPage() }}) {
				$(".loding").removeClass("hide");
				var url = "{{ route('index.index', request()->type) }}" + "?page=" + page;
				$.get(url, function (ret) {
					console.log(ret);
					$(".listbox").append(ret.html);
					$(".loding").addClass("hide");
                    $(".lazy").lazyload({
                        event: "scrollstop",
                        effect : "fadeIn",
                        container: $(".listbox"),
                        load:function ($e) {
                            $e.css({"width":"100%","height":"100%"});
                        }
                    });
				});
			} else {
				$(".ending").removeClass("hide");
			}
		}
    }
    // 采用了防抖动
    var page = 1;
	var scroll = $(".flexitemv.mainbox");
    $(".flexitemv.mainbox").scroll(debounce(realFunc,50));

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