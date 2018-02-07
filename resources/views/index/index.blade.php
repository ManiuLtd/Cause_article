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
			{{--<a href="{{route('index.index')}}" class="flex center item @if(request()->type == '') current @endif"><span class="flex center">热文分享</span></a>--}}
			@foreach($article_type as $type)
				<a href="{{route('index.index',['type'=>$type->id])}}" class="flex center item @if(request()->type == $type->id) current @endif"><span class="flex center">{{ $type->name }}</span></a>
			@endforeach
			<a href="javascript:;" class="flex center bls bls-yjt more"></a>
		</div>
		<div class="flexitemv mainbox">
			<div class="flex banner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						@foreach($banner_list as $value)
							<div class="swiper-slide"><img class="fitimg" src="/uploads/{{ $value->image }}"/></div>
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
					<a href="{{route('article_details',['id'=>$value->id])}}" class="flex lists">
						<div class="img flex center">
							@if(request()->type == 3)
								<img class="lazy" data-original="/index/image/night.jpg" src="/index/image/loading.gif" />
								<i class="flex center bls bls-video"></i>
							@else
								<img class="lazy" data-original="{{$value->pic}}" src="/index/image/loading.gif" />
							@endif
						</div>
						<div class="flexitemv cont">
							<h2 class="flexv">{{$value->title}}</h2>
							<div class="flex base">
								<span class="flex center">
									<i class="flex center bls @if(request()->type == 3) bls-listen @else bls-ck @endif"></i>
									{{$value->read}}
								</span>
								<span class="flex center"><i class="flex center bls bls-time"></i>{{ $value->created_at->toDateString() }}</span>
							</div>
						</div>
					</a>
				@endforeach
			</div>
		</div>
	</div>
	@include('index.public.footer')

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')
	
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/index/js/lazyload.js"></script>
<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>

@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')

<script>
    $(".lazy").lazyload({
        event: "scrollstop",
		effect : "fadeIn",
        container: $(".listbox"),
        load:function ($e) {
            $e.css({"width":"100%","height":"100%"});
        }
    });

    //给分类第一个标签加上选中状态
	@if(request()->type == '')
		$('.flex.center.item').eq(0).addClass('current');
	@endif

    new Swiper ('.swiper-container', {
        loop: true,
        autoplay:1500,
        pagination: '.swiper-pagination',
        autoplayDisableOnInteraction:false
    });

	$('.submit').click(function(){
	    $('#search').submit();
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
                            window.location.href = "{{ route('index.index') }}" + '?' + Math.random();
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