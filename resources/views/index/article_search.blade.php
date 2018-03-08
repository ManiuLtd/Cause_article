<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>搜索</title>
	@include('index.public.css')
	<style>
		.hide{display: none;}
	</style>
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
							<img class="fitimg" src="{{$value->pic}}"/>
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
			<p class="flex center loading hide">正在加载中~</p>
			<p class="flex center ending hide">已全部加载~</p>
		</div>
	@else
		<div class="flexitem center void">
			<p>找不到“<span>{{request()->key}}</span>”，换个关键词试试吧~</p>
		</div>
	@endif

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.footer')

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')
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
     });

	 @if(!$user->brand_id && !$user->phone)
		 new checkForm({
			 form : '#form',
			 btn : '#submit',
			 error : function (ele,err){showMsg(err);},
			 complete : function (ele){
				 var url = $(ele).attr('action'),post = $(ele).serializeArray();
				 showProgress('正在提交');
				 console.log(post);
				 $.post(url,post,function (ret){
					 hideProgress();
					 if(ret.state == 0) {
						 showMsg('完善资料成功', 1, 1500);
						 setTimeout(function () {
							 window.location.href = "{{ route('article_search', request()->key) }}" + '?' + Math.random();
						 }, 1500);
					 } else {
						 showMsg('完善资料失败');
					 }
				 },'json');
			 }
		 });
	@endif

     var page = 1;
     $(".mainbox.result").scroll(function() {
         var scrollTop = Math.ceil($(this).scrollTop()),thisHeight = $(this).height(),boxHeight = $(".listbox").height();
         if((scrollTop + thisHeight) > boxHeight -10) {
             page++;
             if(page < {{ $list->lastPage() }}) {
                 $(".loding").removeClass("hide");
                 var url = "{{ route('article_search', request()->key) }}" + "?page=" + page;
                 $.get(url, function (ret) {
                     console.log(ret);
                     $(".listbox").append(ret.html);
                     $(".loding").addClass("hide");
                 });
             } else {
                 $(".ending").removeClass("hide");
             }
         }
     });

</script>
</html>