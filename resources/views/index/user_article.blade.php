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
			<div class="flex lists">
				<div class="img">
					<img class="fitimg" src="/uploads/{{$value->article['pic']}}"/>
				</div>
				<div class="flexitemv cont">
					<a href="{{route('user_article_details',['id'=>$value->id])}}" class="flexitemv">{{$value->article['title']}}</a>
					<div class="base">
						<span><em>{{$value->read}}</em>阅读</span>
						<span><em>{{$value->share}}</em>分享</span>
						<span><em>{{$value->created_at->diffForHumans()}}</em></span>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<p class="flex center more">没有更多了~</p>
	</div>
</div>
</body>
</html>