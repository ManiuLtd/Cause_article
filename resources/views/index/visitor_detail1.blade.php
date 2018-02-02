<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>头条阅读/分享详情</title>
	@include('index.public.css')
</head>
<body>
<div id="detail" class="flexv wrap">
	<div class="flexitemv mainbox">
		<div class="listbox">
			<div class="flex lists">
				<div class="img">
					<img class="fitimg" src="{{$res->article['pic']}}"/>
				</div>
				<div class="flexitemv cont">
					<a href="javascript:;" class="flexitemv">{{$res->article['title']}}</a>
					<div class="base">
						<span><em>{{$res->read}}</em>阅读</span>
						<span><em>{{$res->share}}</em>分享</span>
						<span><em>{{$res->created_at->diffForHumans()}}</em></span>
					</div>
				</div>
			</div>
			<div class="particulars">
				@foreach($footprint as $value)
					<div class="flex centerv bottom">
						<div class="headimg">
							<img src="{{$value->user['head']}}" class="fitimg">
						</div>
						<div class="flexitemv info">
							<p class="flex centerv">{{$value->user['wc_nickname']}}</p>
							<p class="flex centerv">
								@if($value->type == 1)
									停留<em>{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</em>
								@else
									分享给朋友或微信群
								@endif
							</p>
						</div>
						<div class="flexv end right">
							<p>{{$value->created_at}}</p>
							<a href="{{route('connection',['uid'=>$value->see_uid])}}" class="flex center">找到他</a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
</body>
</html>