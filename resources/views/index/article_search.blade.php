<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>搜索</title>
	@include('index.public.css')
</head>
<body>
<div id="seek" class="flexv wrap">
	<form action="{{route('article_search')}}" method="get">
		<div class="flex center search">
			<div class="flex centerv home-sea">
				<input type="text" name="key" class="flexitem sea-text" value="{{request()->key}}" placeholder="输入关键字，找文章">
				<span class="flex center bls bls-cuo empty"></span>
			</div>
		</div>
	</form>

	@if(count($list) > 0)
	<div class="flexitemv mainbox result">
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
						<span>于建华</span>
						<span>首创</span>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<p class="flex center ">没有更多了~</p>
	</div>
	@else
	<div class="flexitem center void">
		<p>找不到“<span>{{request()->key}}</span>”，换个关键词试试吧~</p>
	</div>
	@endif

	@include('index.public.footer')

	@include('index.public.perfect_information')
</div>
</body>
<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>

@include('index.public.perfect_js')
<script type="text/javascript">
     var input = $('.home-sea input').get(0);
     input.oninput=function () {
         if(input.value != ""){
             $(".empty").css({"display":"block"})
         } else{
             $(".empty").css({"display":"none"})
		 }
     };
     $(".empty").click(function () {
         $('.home-sea input').val("");
         $(".empty").css({"display":"none"})
     })

</script>
</html>