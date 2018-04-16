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
<div id='details' class="flexitemv">
	<div class="info">
		<div class="p1">
			<span class="">姓&ensp;&ensp;&ensp;名：</span>
			<span>
				@if($membership_time)
					{{substr( $message->name, 0, 1 )}}**
				@else
					{{$message->name}} /@if($message->gender == 1) 男士 @else 女士 @endif
				@endif
			</span>
		</div>
		<div class="p1">
			<span class="">手 机 号：</span>
			<span>
				@if($membership_time)
					{{substr( $message->phone, 0, 3 )}}********
				@else
					{{$message->phone}}
				@endif
			</span>
		</div>
		<div class="p1">
			<span class="">年 龄 段：</span>
			<span>{{$message->age}}</span>
		</div>
		<div class="p1">
			<span class="">咨询内容：</span>
			<span>@if($message->type == 1) 健康问题 @elseif($message->type == 2) 加盟事业 @else 其他 @endif</span>
		</div>
	</div>
	<!--end-->

	<div class="flexv centerv dredge">
		@if($membership_time)
			<a href="{{route('open_member')}}" class="flex center button">开通<span>{{ optional($message->user->brand)->name }}</span>事业</a>
		@endif
		<p>让客户第一时间联系到您</p>
	</div>
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