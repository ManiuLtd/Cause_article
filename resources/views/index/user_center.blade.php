<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>个人中心</title>
	@include('index.public.css')
	<link rel="stylesheet" href="/css/index/reset.css">
</head>
<body>
<div id="focus" class="flexv wrap">
	<div class="flexitemv datum">
		<div class="flexv center sub">
			<div class="userimg">
				<img src="{{$res['head']}}" class="fitimg">
			</div>
			<h2 class="flex center name">{{$res['wc_nickname']}}</h2>
			<p class="flex center lock">
				@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($res['membership_time'])))
					谁查看我的功能：<span>未开通</span>
				@else
					有效期至：<span>{{ \Carbon\Carbon::parse($res['membership_time'])->toDateString() }}</span>
				@endif
			</p>
			<div class="flexv centerv front">
				<a href="{{route('user_article')}}" class="flexitemv center myfront">
					<em class="flex">{{$res['user_article']}}</em>
					<div class="flex">
						<span class="flex center">我的头条</span>
						<i class="flex center bls bls-yjt"></i>
					</div>
				</a>
				<div class="flex line"></div>
				<a href="{{route('read_share')}}" class="flexitemv center myfront">
					<em class="flex">{{$res['read_share']}}</em>
					<div class="flex">
						<span class="flex center">谁查看我的头条 </span>
						<i class="flex center bls bls-yjt"></i>
					</div>
				</a>
			</div>
		</div>
		
		<div class="flexv func">
			<a href="{{route('user_basic')}}" class="flexitem centerv card">
				<i class="flex center bls bls-mp" style="background:#fbc45d; "></i>
				<span class="flex center text">设置名片</span>
			</a>
			<a href="{{route('open_member')}}" class="flexitem centerv kt">
				<i class="flex center bls bls-ck" style="background:#f68f66; "></i>
				<span class="flex center text">开通谁查看我的功能</span>
			</a>
			<a href="javascript:;" class="flexitem centerv hy">
				<i class="flex center bls bls-jhy" style="background:#67cef9; "></i>
				<span class="flex center text">
					@if($res['extension_type'] == 0)
						首次推荐5位好友，立享5天试用期
					@elseif($res['extension_type'] == 1)
						继续推荐10位好友，立享5天试用期
					@elseif($res['extension_type'] == 2)
						继续推荐20位好友，立享10天试用期
					@elseif($res['extension_type'] == 3)
						继续推荐30位好友，立享10天试用期
					@elseif($res['extension_type'] == 4)
						继续推荐40位好友，立享20天试用期
					@else
						邀请好友
					@endif
				</span>
				<img id="code" src="{{ $pic }}" alt="" style="display: none">
				<img src="{{ $head }}" id="userImg" alt="" style="display: none;">
			</a>
		</div>
	</div>

	<canvas id="myCanvas" style="display: none"></canvas>
	<img src="/poster.jpg" id="background" style="display: none">

	@include('index.public.footer')

	<!--提示-->
	<div class="flex center hint">
		<div class="mask"></div>
		<div class="content">
			<div class="flex center">邀请海报已通过微信消息发送给你</div>
			<a href="javascript:;" class="flex center" id="close">请去公众号查看</a>
		</div>
	</div>
</div>
</body>
<!-- <script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script> -->
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.js"></script>
<script type="text/javascript" src="/js/common/functions.js"></script>
<script type="text/javascript">
	$(function () {
		$(".hy").click(function () {
			@if($res['extension_image'] != '')
			showProgress('正在发送海报');
				$.post("{{route('inviting')}}",{url:"{{$res['extension_image']}}", type:1, _token:"{{csrf_token()}}"},function (ret) {
				hideProgress();
				if(ret.state == 0) {
					$(".hint").css({"display":"block"});
					$(".hint").find(".content").addClass('trans');
				} else {
					showMsg(ret.errormsg)
				}
			});
			@else
			showProgress('正在发送海报');
			//canvas画图
			var image = document.querySelector('#background');
			var userimg = document.querySelector('#userImg');
			var c=document.getElementById("myCanvas");
			var ctx=c.getContext("2d");
			c.width = image.width;
			c.height = image.height;
			ctx.drawImage(image,0,0);
			ctx.save();//保存当前环境的状态。否则之后画圆的时候，可见区域只有圆的区域（切记注意）
			ctx.beginPath();
			ctx.strokeStyle = '#fff';
			userBorderSize = 50;
			userBorderX = image.width/2;
			userBorderY = 100;
			ctx.font="25px Arial";
			ctx.textAlign = 'center';
			ctx.fillStyle = '#fff';
			ctx.fillText("我是{{session()->get('nickname')}}",image.width/2,200);
			ctx.arc(userBorderX,userBorderY,userBorderSize,0,2*Math.PI);
			ctx.stroke();
			ctx.clip();
            ctx.drawImage(userimg, 0, 0, userimg.width, userimg.height, userBorderX - userBorderSize, userBorderY - userBorderSize, userBorderSize*2, userBorderSize*2);
			ctx.restore();
			var qrcode = document.getElementById('code');
			ctx.drawImage(qrcode, 0, 0, 426, 426, 200, 610, 200, 200);
			try {
				var data = c.toDataURL('image/jpeg');
			} catch (e) {
				alert(e);
			}
			$.post("{{route('inviting')}}",{url:data, type:2, _token:"{{csrf_token()}}"},function (ret) {
				if(ret.state == 0) {
                    hideProgress();
					$(".hint").css({"display":"block"});
					$(".hint").find(".content").addClass('trans');
				} else {
					showMsg(ret.errormsg);
				}
            });
			@endif
		});

		$(".mask").click(function () {
			$(".hint").css({"display":"none"});
			window.location.reload();
		});

		$("#close").click(function () {
            WeixinJSBridge.call('closeWindow');
        })
    })

    //img转base64
    function convertImgToBase64(url, callback) {
        var canvas = document.createElement('CANVAS'),
            ctx = canvas.getContext('2d'),
            img = new Image;
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            canvas.height = img.height;
            canvas.width = img.width;
            ctx.drawImage(img, 0, 0);
            var dataURL = canvas.toDataURL('image/jpeg');
            img.src = dataURL;
            callback(img);
        };
        img.src = url;
    }

</script>
</html>