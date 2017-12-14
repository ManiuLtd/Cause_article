<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>首页</title>
	@include('index.public.css')
</head>
<body>
<div id="home" class="flexv wrap">
	<div class="flexitemv box">
		<div class="flex nav">
			<a href="{{route('index.index')}}" class="flexitem center item @if(request()->type == '') current @endif"><span class="flex center">最新资讯</span></a>
			<a href="{{route('index.index',['type'=>1])}}" class="flexitem center item @if(request()->type == 1) current @endif"><span class="flex center">事业资讯</span></a>
			<a href="{{route('index.index',['type'=>2])}}" class="flexitem center item @if(request()->type == 2) current @endif"><span class="flex center">产品资讯</span></a>
			<a href="{{route('index.index',['type'=>3])}}" class="flexitem center item @if(request()->type == 3) current @endif"><span class="flex center">直销资讯</span></a>
			<a href="javascript:;" class="flex center bls bls-yjt more"></a>
		</div>
		<div class="flexitemv mainbox">
			<div class="flex banner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						@foreach($banner_list as $value)
						<div class="swiper-slide"><img class="fitimg" src="/uploads/{{$value->image}}"/></div>
						@endforeach
					</div>
					<div class="swiper-pagination"></div>
				</div>
			</div>
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
				<div class="flex lists">
					<div class="img">
						<img class="fitimg" src="/uploads/{{$value->pic}}"/>
					</div>
					<div class="flexitemv cont">
						<a href="{{route('article_details',['id'=>$value->id])}}" class="flexitemv">{{$value->title}}</a>
						<div class="base">
							<span><em>{{$value->read}}</em>阅读</span>
							<span><em>{{$value->share}}</em>分享</span>
							<span>轩轩</span>
							<span>首创</span>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	@include('index.public.footer')

	@include('index.public.perfect_information')
	
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>

@include('index.public.perfect_js')

<script>
	$('.submit').click(function(){
	    $('#search').submit();
	})
</script>

</html>