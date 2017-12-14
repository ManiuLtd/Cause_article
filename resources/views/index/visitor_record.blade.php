<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>访客记录</title>
	@include('index.public.css')
</head>
<body>
<div id="visitor" class="flexv wrap">
	@if(count($list) > 0)
	<div class="flexitemv mainbox box">
		@foreach($list as $value)
		<div class="listbox">
			<div class="flex lists">
				<div class="img">
					<img class="fitimg" src="/uploads/{{ $value['article']['pic'] }}"/>
				</div>
				<div class="flexitemv cont">
					<a href="javascript:;" class="flexitemv">{{ $value['article']['title'] }}</a>
					<div class="base">
						<span><em>{{ $value['read'] }}</em>阅读</span>
						<span style="color:#969696"><em>{{ $value['share'] }}</em>分享</span>
						<span><em>{{ $value['new_count'] }}</em>新访问</span>
					</div>
				</div>
			</div>
			<div class="flex details">
				<div class="flex center imgbox">
					@foreach($value['user'] as $user)
						<div class="flex center userimg"><img src="{{$user['head']}}" class="fitimg"></div>
					@endforeach
				</div>
				<div class="flexitem endh lock">
					<a href="{{route('visitor_details',['id'=>$value['id']])}}" class="flex center">查看详情</a>
				</div>
			</div>
		</div>
		@endforeach
	</div>
	@else
		<div class="flexitem center void">
			<p style="font-size: 18px">暂无访客,快把文章分享给友好吧~</p>
		</div>
	@endif

	@include('index.public.footer')
</div>
</body>
</html>