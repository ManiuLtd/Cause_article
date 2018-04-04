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

	<div class="flexitemv mainbox result mescroll" id="mescroll">
		<div class="listbox" id="listbox">

		</div>
	</div>

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.footer')

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')
</div>
</body>
<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')

@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '找不到"'.request()->key.'"，换个关键词试试吧~', 'html' => 'listbox', 'route' => route('article_search', request()->key), 'lists' => $list, 'lazyload' => 0])

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

</script>
</html>