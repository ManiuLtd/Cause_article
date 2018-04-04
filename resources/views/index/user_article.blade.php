<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>@if($user->id != session('user_id')) {{ $user->wc_nickname }}的头条 @else 我的头条 @endif</title>
	@include('index.public.css')
</head>
<body>
<div id="headlines" class="flexv wrap">
	<div class="flexitemv mainbox mescroll" id="mescroll">
		<div class="listbox" id="listbox">

		</div>
	</div>

	<div class="flex tabbars">
		<div class="flexitem center middle">
			<a href="/" class="flexv center user">
				<span class="flex userimg">
					<img class="fitimg" src="{{ $user->head }}"/>
				</span>
				<em class="flex center">首页</em>
			</a>
		</div>
		<a @if(\Carbon\Carbon::parse($user->membership_time)->gt(\Carbon\Carbon::now())) href="tel:{{ $user->phone }}" @else href='javascript:;' id='phone' @endif" class="flexv center item">
			<i class="flex center bls bls-dh"></i>
			<em class="flex center">拨手机</em>
		</a>
		<a href="javascript:;" class="flexv center item wx">
			<i class="flex center bls bls-weixin"></i>
			<em class="flex center">加微信</em>
		</a>
		<a href="{{ route('chatroom', $user->id) }}" id="data" class="flexv center item"  id="data">
			<i class="flex center bls bls-zx-ing"></i>
			<em class="flex center">在线资询</em>
		</a>
	</div>

	<!--提示-->
	<div class="flex center hint">
		<div class="mask"></div>
		<div class='content'>
			<h3 class="flex center">加我免费咨询</h3>
			<div class="qrcode">
				<img src="{{ $user->qrcode }}" class="fitimg">
			</div>
			<p class="flex center">长按识别二维码</p>
		</div>
	</div>

</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '暂无相关数据~', 'html' => 'listbox', 'route' => route('user_article'), 'lists' => $list, 'lazyload' => 0])

<script>

    $('#phone').click(_.throttle(function () {
        @if($user->id == session('user_id'))
        	showMsg('您未开通此服务');
		@else
			showMsg('该用户未开通此服务');
			$.get("{{ route('tip_user_qrcode', $user->id) }}", function () {});
        @endif
    }, 4000, { 'trailing': false }));

    //	加微信
	@if(\Carbon\Carbon::parse($user->membership_time)->gt(\Carbon\Carbon::now()))
		@if($user->qrcode)
			$(".wx").click(function () {
				$(".hint").show();
				$(".hint").find(".content").addClass('trans');
			});
		@else
			$(".wx").click(function () {
				showMsg('该用户尚未上传二维码', 0, 1500);
				{{--$.get("{{ route('tip_user_qrcode', $user->id) }}", function () {});--}}
			});
		@endif
		$(".mask").click(function(){
			$(".hint").hide();
		});
    @else
		$(".wx").click(_.throttle(function () {
		@if($user->id == session('user_id'))
        	showMsg('您未开通此服务');
		@else
			showMsg('该用户未开通此服务');
        	$.get("{{ route('tip_user_qrcode', $user->id) }}", function () {});
		@endif
		}, 4000, { 'trailing': false }));

	@endif

    wx.config(<?php echo $js->config(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) ?>);

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