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
	<div class="flexitemv mainbox">
		@foreach($list as $value)
		<div class="flexv centerv lists">
			<div class="flex top">
				<div class="headimg">
					<img src="{{$value->user['head']}}" class="fitimg">
				</div>
				<div class="flexitemv info">
					<p class="flex centerv">
						@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($value->user['membership_time'])))
							{{substr( $value->name, 0, 1 )}}**
						@else
							{{$value->name}}
						@endif
					</p>
					<p class="flex centerv">
						@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($value->user['membership_time'])))
							{{substr( $value->phone, 0, 3 )}}********
						@else
							{{$value->phone}}
						@endif
					</p>
				</div>
				<div class="flex endh">{{$value->created_at}}</div>
			</div>
			<a href="{{route('message_detail',['id'=>$value->id])}}" class="flex center">点击查看留言详情</a>
		</div>
		@endforeach
	</div>
</div>
</body>
</html>