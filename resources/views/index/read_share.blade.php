<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>阅读分享</title>
	@include('index.public.css')
</head>
<body>
<div id="read" class="flexv wrap">
	<div class="flexitemv mainbox box">
		<div class="flex center title">
			<span class="flex center read elect">阅读</span>
			<span class="flex center share">分享</span>
		</div>
		
		<div class="reads">
			@foreach($list as $value)
			@if($value->type == 1)
			<div class="listbox">
				<div class="flex centerv top">
					<div class="headimg">
						<img src="{{$value->user['head']}}" class="fitimg">
					</div>
					<div class="flexitemv info">
						<p class="flex centerv">{{$value->user['wc_nickname']}}</p>
						<p class="flex centerv">{{$value->created_at->diffForhumans()}}</p>
					</div>
					<div class="flex center">停留<em>{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</em></div>
				</div>
				<div class="flex lists">
					<div class="img">
						<img class="fitimg" src="{{$value->article['pic']}}"/>
					</div>
					<div class="flexitemv cont">
						<a href="{{route('visitor_details',['id'=>$value->uaid])}}" class="flexitemv">{{$value->article['title']}}</a>
					</div>
				</div>
			</div>
			@endif
			@endforeach
		</div>
		
		<div class="shares" style="display:none;">
			@foreach($list as $value)
			@if($value->type == 2)
			<div class="listbox">
				<div class="flex centerv top">
					<div class="headimg">
						<img src="{{$value->user['head']}}" class="fitimg">
					</div>
					<div class="flexitemv info">
						<p class="flex centerv">{{$value->user['wc_nickname']}}</p>
						<p class="flex centerv">{{$value->created_at->diffForhumans()}}</p>
					</div>
					<div class="flex center">分享给朋友</div>
				</div>
				<div class="flex lists">
					<div class="img">
						<img class="fitimg" src="{{$value->article['pic']}}"/>
					</div>
					<div class="flexitemv cont">
						<a href="{{route('visitor_details',['id'=>$value->uaid])}}" class="flexitemv">{{$value->article['title']}}</a>
					</div>
				</div>
			</div>
			@endif
			@endforeach
		</div>
	</div>
</div>
</body>
<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">
	$(".read").click(function(){
        $(".title span").removeClass('elect');
	    $(this).addClass('elect');
	    $('.reads').css({'display':'block'});
		$('.shares').css({'display':'none'});
	});
    $(".share").click(function(){
        $(".title span").removeClass('elect');
        $(this).addClass('elect');
        $('.shares').css({'display':'block'});
        $('.reads').css({'display':'none'});
    })
</script>
</html>