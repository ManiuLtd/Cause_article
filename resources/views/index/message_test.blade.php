<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>留言管理</title>
	@include('index.public.css')
</head>
<body>
<div id="mesag" class="flexv wrap">
    <div class="flex center title">
        <a href="{{ route('test', 1) }}" class="flex center read @if(request()->type == 1)elect @endif">用户咨询留言</a>
        <a href="{{ route('test', 2) }}" class="flex center share @if(request()->type == 2)elect @endif">家庭保障留言</a>
    </div>
	<div class="flexitemv mainbox mescroll" id="mescroll">

		@if(!$time && request()->type == 2)
			<div class="flexv center ordinary">
				<h2 class="tit">您还未开通正式会员</h2>
				<p class="s-tit">开通后才可以体验"家庭保障留言"功能</p>
				<a href="{{ route('open_member') }}" class="flex center btn">去开通</a>
			</div>
		@endif
	</div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>

	@if($time && request()->type == 2)

		@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '还没有收到家庭保障留言哦~', 'html' => 'mescroll', 'route' => route('test', request()->type), 'lists' => $lists, 'lazyload' => 0])

    @elseif(request()->type == 1)

		@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '还没有收到留言哦~', 'html' => 'mescroll', 'route' => route('test', request()->type), 'lists' => $lists, 'lazyload' => 0])

	@endif

</html>