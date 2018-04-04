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
	<div class="flexitemv mainbox mescorll" id="mescorll">
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

		<div class="sharerbox" id="sharerbox">

		</div>
	</div>
</div>
</body>

<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'mescorll', 'tip' => '', 'html' => 'sharerbox', 'route' => route('visitor_details', request()->id), 'lists' => $footprint, 'lazyload' => 0])

</html>