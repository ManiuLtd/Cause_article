<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	<title>最近看我的</title>
	@include('index.public.css')
	<style>
		.loading, .ending{
			font-size: 1.3rem;
			color: #888888;
			padding: 10px 0;
		}
		.hide {display: none;}
	</style>
<body>
<div id="lately" class="flexv wrap">
	<div class="flexitemv mainbox">
		<p class="flex centerv explain">说明：文章未被阅读时，记得发给好友。</p>
		<div class="flex lists">
			<div class="img">
				<img class="fitimg" src="{{ $res->article->pic }}">
			</div>
			<div class="flexitemv cont">
				<h2 class="flexitem">{{ $res->article->title }}</h2>
				<div class="between base">
					<span><em>{{ $res->created_at->diffForHumans() }}</em></span>
					<span><em>{{ $res->read }}</em>浏览</span>
				</div>
			</div>
			<a href="{{ route('article_details', $res->article->id) }}" class="link"></a>
		</div>

		<p class="flex center more">浏览 / 分享者</p>

		<div class="sharerbox">
			@foreach($footprint as $value)
				<div class="sharer">
					<div class="between info">
						<div class="flex centerv kf">
							<div class="flex center img">
								<img src="{{ $value->user->head }}" class="fitimg">
							</div>
							<div class="flexv centerh text">
								<div class="flexv tex">{{ $value->user->wc_nickname }}</div>
								<div class="data">
									<span>{{ date('Y-m-d H:s', strtotime($value->created_at)) }}</span>
								</div>
							</div>
						</div>
						<div class="flexv center time">
							@if($value->type == 1)
								<span class="flex"><em>{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</em></span>
								<span class="flex">阅读时间</span>
							@else
								<span style="font-size: 1.4rem;color: red">分享朋友</span>
							@endif
						</div>
						<a href="{{ route('visitor_record_see', $value->id) }}" class="flex center also-btn">他还看了</a>
					</div>
					<div class="relation">
						<p class="text">通过以下人脉关系链接传到-{{ $value->user->wc_nickname }}</p>
						<div class="flex box">
							<div class="flexv center img">
								<img class="flex" src="{{ $res->user->head }}">
								<span class="flexv">{{ $res->user->wc_nickname }}</span>
							</div>
							@if(count($value->extension))
								@foreach($value->extension as $user)
									<i class="flex centerh bls bls-right"></i>
									<div class="flexv center img">
										<img class="flex" src="{{ $user['user']['head'] }}">
										<span class="flexv">{{ $user['user']['wc_nickname'] }}</span>
									</div>
								@endforeach
							@endif
							<i class="flex centerh bls bls-right"></i>
							<div class="flexv center img">
								<img class="flex" src="{{ $value->user->head }}">
								<span class="flexv">{{ $value->user->wc_nickname }}</span>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		<p class="flex center loading hide">正在加载中~</p>
		<p class="flex center ending hide">已全部加载~</p>
	</div>
</div>
</body>

<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script>
    // 简单的防抖动函数
    function debounce(func, wait) {
        // 定时器变量
        var timeout;
        return function() {
            // 每次触发 scroll handler 时先清除定时器
            clearTimeout(timeout);
            // 指定 xx ms 后触发真正想进行的操作 handler
            timeout = setTimeout(func, wait);
        };
    };
    // 实际想绑定在 scroll 事件上的 handler
    function realFunc(){
        var scrollTop = Math.ceil(scroll.scrollTop()),thisHeight = scroll.height(),boxHeight = $(".sharerbox").height();
        if((scrollTop + thisHeight) > boxHeight - 10) {
            page++;
            if(page < Number({{ $footprint->lastPage() }})+Number(1) ) {
				$(".loding").removeClass("hide");
				var url = "{{ route('visitor_details', request()->id) }}" + "?page=" + page;
				$.get(url, function (ret) {
					console.log(ret);
					$(".sharerbox").append(ret.html);
					$(".loding").addClass("hide");
				});
            } else {
                $(".ending").removeClass("hide");
            }
        }
    }
    // 采用了防抖动
    var page = 1;
    var scroll = $(".flexitemv.mainbox");
    scroll.scroll(debounce(realFunc,50));
</script>

</html>