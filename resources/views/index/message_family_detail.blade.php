<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>咨询详情</title>
	@include('index.public.css')
</head>
<body>
<div id='details' class="flexitemv mainbox wrap">
	<div class="info">
		<div class="p1">
			<span class="">姓&ensp;&ensp;&ensp;名：</span>
			<span>{{ $message->name }}</span>
		</div>
		<div class="p1">
			<span class="">手 机 号：</span>
			<span>{{ $message->phone }}</span>
		</div>
		<div class="p1">
			<span class="">年&ensp;&ensp;&ensp;龄：</span>
			<span>{{ \Carbon\Carbon::now()->year - \Carbon\Carbon::parse($message->age)->year }}</span>
		</div>
		<div class="p1">
			<span class="">地&ensp;&ensp;&ensp;区：</span>
			<span>{{ $message->region }}</span>
		</div>
		<div class="p1">
			<span class="">家庭结构：</span>
			<span>{{ $message->family }}</span>
		</div>
		<div class="p1">
			<span class="">年 收 入：</span>
			<span>{{ $message->income }}</span>
		</div>
		<div class="p1">
			<span class="">咨询内容：</span>
			<span>{{ $message->type }}</span>
		</div>
	</div>
	<!--end-->
	{{--<div class="flexv centerv dredge">--}}
		{{--<a href="javascript:;" class="flex center button">开通<span>国珍</span>事业</a>--}}
		{{--<p>让客户第一时间联系到您</p>--}}
	{{--</div>--}}
	<!--end-->
	<div class="flexv center qrcode">
		<div class="img">
			<img src="/kf_qrcode.jpg" />
		</div>
		<p>长按识别二维码</p>
		<p>联系客服咨询</p>
	</div>
</div>
</body>
</html>