<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>个人中心</title>
	@include('index.public.css')
	<link rel="stylesheet" href="/css/index/reset.css">
</head>
<body>
<div id="focus" class="flexv wrap">
	<div class="flexitemv mainbox datum">
		<div class="flexv sub">
			<div class="flexitem centerv userbox">
				<div class="userimg">
					<img src="{{ $user->head }}" class="fitimg">
				</div>
				<div class="flexitemv info">
					<div class="flex name">
						<h2 class="flex center">{{ $user->wc_nickname }}</h2>
					</div>
					@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($user->membership_time)))
						<p class="flex lock">谁查看我的功能：<span>未开通</span></p>
					@else
						<p class="vip">正式会员</p>
						<p class="flex lock">
							有效期至：<span>{{ \Carbon\Carbon::parse($user->membership_time)->toDateString() }}</span>
						</p>
					@endif
				</div>
				<a href="{{ route('open_member') }}" class="flex center renew">续费会员</a>
			</div>

			<div class="flexv centerv around front">
				<a href="{{route('user_article')}}" class="flexitemv center myfront">
					<em class="flex">{{ $user->user_article_count }}</em>
					<div class="flex">
						<span class="flex center">我的头条</span>
						<i class="flex center bls bls-yjt"></i>
					</div>
				</a>
				<div class="flex line"></div>
				<a href="{{route('read_share', 1)}}" class="flexitemv center myfront">
					<em class="flex">{{ $user->user_foot_count }}</em>
					<div class="flex">
						<span class="flex center">谁查看我的头条 </span>
						<i class="flex center bls bls-yjt"></i>
					</div>
				</a>
			</div>
		</div>

		<div class="flipbox">
			<div class="bor">
				@foreach($orders as $order)
					<div class="flex centerv flip">
						<i class="flex center bls bls-horn"></i>
						<div class="flex text"> 恭喜“<span class="flexv name">{{ str_limit($order->user->wc_nickname, 10) }}</span>”成功开通会员进行获客展示</div>
					</div>
				@endforeach
			</div>
		</div>
		
		<div class="flexv func">
			<a href="{{route('user_basic')}}" class="flexitem centerv card">
				<i class="flex center bls bls-mp" style="background:#fbc45d; "></i>
				<span class="flex center text">设置名片</span>
			</a>
			<a href="{{ route('extension_rule') }}" class="flexitem centerv">
				<i class="flex center bls bls-jhy" style="background:#67cef9; "></i>
				<span class="flex center text">邀请好友<span>（可得30%佣金）</span></span>
			</a>
			<a href="{{ route('index.extension') }}" class="flexitem centerv tg">
				<i class="flex center bls bls-generalize" style="background:#35f921; "></i>
				<span class="flex center text">推广中心</span>
			</a>
			<a href="{{ route('message_list', 1) }}" class="flexitem centerv tg">
				<i class="flex center bls bls-consult" style="background:#ffc0cb"></i>
				<span class="flex center text">留言管理</span>
			</a>
		</div>

		<div class="picture" style="padding-bottom: 15px">
			<div class="between title">
				<div class="tex">展示美图</div>
				<a href="{{ route('extension_photo_list') }}" class="flex center more">更多 <i class="flex center bls bls-yjt"></i></a>
			</div>
			<div class="flex imgbox">
				@foreach($photos as $photo)
					<a href="{{ route('extension_poster', $photo->id) }}" class="flex center" style="width:21.4%;height:11rem;padding-left:1rem;">
						<img data-original="{{ $photo->url }}" class="lazy" src="/index/image/loading.gif" style="max-width: 100%">
					</a>
				@endforeach
			</div>
		</div>
	</div>

	<!--提示-->
	@if(request()->dealer && !$user->subscribe)
		@if(request()->type == 'ex_user' || request()->type == 'become_extension')
			<div class="flex center gzh" style="display: block;">
				<div class="mask"></div>
				<div class='content'>
					<h3 class="flex center">关注公众号享受更多功能</h3>
					<div class="qrcode">
						<img src="/qrcode.jpg" class="fitimg">
					</div>
					<p class="flex center">长按识别二维码</p>
				</div>
			</div>
		@endif
	@else
		@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')
	@endif

	@include('index.public.footer')
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>
<script type="text/javascript" src="/js/common/functions.js"></script>
<script src="/index/js/lazyload.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')
<script type="text/javascript">
    $(".cuo").hide();
    //图片延迟加载
    $(".lazy").lazyload({
        event: "scrollstop",
		effect : "fadeIn",
		load:function ($e) {
			$e.css({"width":"100%","height":"100%"});
        }
    });

    //滚动广告
    setInterval(function roll() {
        var objh = $('.flip').height();
        $(".flipbox .bor").append($(".flipbox .bor .flip").first().height(0).animate({"height":objh+"px"},500));
    },2000);

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public._infomation_js')

</script>
</html>