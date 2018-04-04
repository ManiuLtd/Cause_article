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
<div id="read" class="flexv">
	<div class="flexitemv mainbox box">
		<div class="flex center title">
			<a href="{{ route('read_share', 1) }}" class="flex center read @if(request()->type == 1) elect @endif">阅读</a>
			<a href="{{ route('read_share', 2) }}" class="flex center share @if(request()->type == 2) elect @endif">分享</a>
		</div>
		
		<div class="shares" id="shares">

		</div>
	</div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'body', 'tip' => '', 'html' => 'shares', 'route' => route('read_share', request()->type), 'lists' => $lists, 'lazyload' => 0])

</html>