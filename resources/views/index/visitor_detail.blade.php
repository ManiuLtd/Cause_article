<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	<title>最近看我的</title>
	@include('index.public.css')
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
								<div class="tex">{{ $value->user->wc_nickname }}</div>
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
								<span class="flex">{{ $res->user->wc_nickname }}</span>
							</div>
							@if(count($value->extension))
								@foreach($value->extension as $user)
									<i class="flex centerh bls bls-right"></i>
									<div class="flexv center img">
										<img class="flex" src="{{ $user['user']['head'] }}">
										<span class="flex">{{ $user['user']['wc_nickname'] }}</span>
									</div>
								@endforeach
							@endif
							<i class="flex centerh bls bls-right"></i>
							<div class="flexv center img">
								<img class="flex" src="{{ $value->user->head }}">
								<span class="flex">{{ $value->user->wc_nickname }}</span>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
</body>
</html>