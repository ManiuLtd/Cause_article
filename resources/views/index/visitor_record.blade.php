<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>访客记录</title>
	@include('index.public.css')
</head>
<body>
<div id="visitor" class="flexv wrap">
	<div class="flex flipbox">
		<i class="flex center bls bls-horn"></i>
		<div class="flexitem center bor">
			<div class="flex flip">
				<div class="flex center text"> 开通VIP后，可查看访客记录，精准锁定潜在客户</div>
			</div>
		</div>
	</div>
	<div class="flexitemv mainbox box">
        <div class="flexv centerv around front">
            <a href="javascript:;" class="flexitemv center myfront">
                <em class="flex">{{ $today_see }}</em>
                <div class="flex">
                    <span class="flex center">今日浏览</span>
                </div>
            </a>
            <div class="flex line"></div>
            <a href="{{ route('visitor_prospect') }}" class="flexitemv center myfront">
                <em class="flex">{{ count($prospect) }}</em>
                <div class="flex">
                    <span class="flex center">准客户</span>
                </div>
            </a>
        </div>
		@if(count($list) > 0)
			<div class="lists">
				@foreach($list as $value)
					<div class="listbox">
						<div class="flex lists">
							<div class="img">
								<img class="fitimg" src="{{ $value['article']['pic'] }}"/>
							</div>
							<div class="flexitemv cont">
								<h2 class="flexitemv">{{ $value['article']['title'] }}</h2>
								<div class="between base">
									<span><em>{{ \Carbon\Carbon::parse($value['created_at'])->toDateString() }}</em></span>
									<span><em>{{ $value['read'] }}</em>浏览</span>
									<span class="flex center"><em>{{ $value['user_count'] }}</em></span>
								</div>
							</div>
							<a href="{{ route('article_details', $value['article']['id']) }}" class="link"></a>
						</div>
						<div class="flex details">

								<div class="flex center imgbox">
									@if(\Carbon\Carbon::parse($member_time)->gt(\Carbon\Carbon::parse('now')))
										@foreach($value['user'] as $user)
											<div class="flex center userimg"><img src="{{ $user['head'] }}" class="fitimg"></div>
										@endforeach
									@else
										@foreach($value['user'] as $user)
											<div class="flex center userimg"><i class="flex center bls bls-gr"></i></div>
										@endforeach
									@endif
								</div>
							<div class="flexitem endh lock">
								<a href="{{ route('visitor_details', $value['id']) }}" class="flex center">谁看了？</a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@else
		<div class="flexitem center void">
			<p style="font-size: 18px">暂无访客,快把文章分享给好友吧~</p>
		</div>
	@endif

	@include('index.public.footer')

</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script>
    //滚动提示
    var num = 0;
    setInterval(function () {
        if (num <= -($(".text").width())) {
            num = $(".text").width();
        }
        num -= 1;
        $(".flip").css({
            left: num
        })
    },35);
</script>
</html>