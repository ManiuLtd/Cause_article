<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<meta name="referrer" content="never">
	<title>{{$res->title}}</title>
	@include('index.public.css')
	<style>
		.box img{ max-width: 100% !important; height:auto !important; }
		.box iframe{ width: 100% !important; height:auto !important; }
	</style>
</head>
<body>
<div @if($res->type == 3) id="listen" @else id="article" @endif class="flexv wrap">
	<div class="flexitemv mainbox contents" @if($res->type == 3) style="padding:1.2rem" @endif>
		@if($res->type == 3)
			<div class="info">
				<h1>{{ $res->title }}</h1>
				<div class="bottom"><span>{{\Carbon\Carbon::parse($res->created_at)->toDateString()}}</span><a href="javascript:;">轩轩</a></div>
			</div>
			<div id="audio">
				<div class="flex centerv inner">
					<div class="flex center icon" data-src="{{ json_decode($res->audio, true)['src'] }}"></div>
					<div class="flexitemv media">
						<h3 class="flexv centerh">{{ json_decode($res->audio, true)['title'] }}</h3>
						<p class="flexv centerh">{{ json_decode($res->audio, true)['desc'] }}</p>
						<div class="flex progress"><em></em><span class="flex"></span></div>
						<div class="flex centerv duration">
							<span class="flexitem">00:00</span>
							<em class="flex">00:00</em>
						</div>
					</div>
				</div>
			</div>
			<div class="body">
				{!! $res->details !!}
			</div>
		@else
			<div class="title max">
				<h2 class="flex">{{ $res->title }}</h2>
				<div class="flex subhead">
					<span class="date">{{\Carbon\Carbon::parse($res->created_at)->toDateString()}}</span>
					<span class="name">轩轩</span>
					<a href="{{route('index.index')}}" class="site">事业头条</a>
				</div>
				<div class="box">
					{!! $res->details !!}
				</div>
			</div>

			<div class="flex center unfold">
				<div class="flex center unfoldbox">
					<p>展开全文</p>
					<i class="flex center bls bls-bottom"></i>
				</div>
			</div>
		@endif
		
		<div class="flexv centerv user-info">
			<div class="userimg">
				<img src="/kf_login.png" class="fitimg" style="border-radius: 50%">
			</div>
			<p class="flex center name">轩轩</p>
			<div class="flex centerh mesg">
				<span>事业头条</span>
				<span>136***4613</span>
			</div>
			<div class="buttons">
				<a href="javascript:;" class="flex center phone">
					<i class="flex center bls bls-dh"></i>
					<span>打电话</span>
				</a>
				<a href="javascript:;" class="flex center book" style="background:#07BD13">
					<i class="flex center bls bls-weixin"></i>
					<span>加微信</span>
				</a>
			</div>
			<span class="row"></span>
			<span class="col"></span>
			<span class="row last"></span>
			<span class="col last"></span>
		</div>
		
		{{--<div class="flexv center qrcode">--}}
			{{--<div class="img">--}}
				{{--<img src="/qrcode.jpg" class="fitimg">--}}
			{{--</div>--}}
			{{--<p>马上加我微信沟通</p>--}}
			{{--<a href="javascript:;" class="flex center bls bls-kefu service"></a>--}}
		{{--</div>--}}
		
		<div class="flexv center text-box">
			<p>本文为 <span>轩轩</span> 发布，不代表事业头条立场</p>
			<p>若内容不规范或涉及违规，可立即 <a href="{{ route('report',['article_id'=>$res->id, 'type'=>1]) }}">举报/报错</a></p>
		</div>

		{{--<a href="javascript:;" id="cut" class="flex center cut">免费换成我的名片 >></a>--}}
	</div>

	<div class="flex center fixed-btn">
		<a href="javascript:;" id="cut" class="flex center cut">免费换成我的名片 >></a>
	</div>
	
	<!--提示-->
	<div class="flex center hint">
		<div class="mask"></div>
		<div class='content'>
			<h3 class="flex center">加我免费咨询</h3>
			<div class="qrcode">
				<img src="/" class="fitimg">
			</div>
			<p class="flex center">长按识别二维码</p>
		</div>
	</div>

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')

</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.15/clipboard.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    $('#cut').click(function () {
        @if(!$user->brand_id && !$user->phone)
            $(".alert").css({"display":"block"});
            $(".alert").find(".content").addClass('trans');
            //  品牌
            @include('index.public._brand_list')
		@else
            window.location.href = "{{route('become_my_article',['article_id'=>$res->id])}}";
		@endif
    });

    //	关闭
    $(".cuo").click(function(){
        $(".alert").css({"display":"none"});
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
						showMsg('完善资料成功', 1, 2000);
						setTimeout(function () {
							window.location.href = "{{ route('article_details', request()->article) }}";
						}, 2000);
					} else {
						showMsg('完善资料失败');
					}
				},'json');
			}
		});
    @endif
</script>

<script type="text/javascript">
	//  展示全部
	$(".unfold").click(function () {
        $(".title").removeClass('max');
        $(".unfold").text('');
    });

	//	事业宝典
	$(".book").click(function () {
		$(".hint").css({"display":"block"});
		$(".hint").find(".content").addClass('trans');
	});
	$(".mask").click(function(){
		$(".hint").css({"display":"none"});
	});

    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$package['appId']}}', // 必填，公众号的唯一标识
        timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$package['signature']}}',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        //分享微信好友
        wx.onMenuShareAppMessage({
            title: '{{$res->title}}', // 分享标题
            desc: '{!! subtext(preg_replace('/&[a-z]+;/i',"", str_replace("\n","",preg_replace('/<\/?[^>]+>/i',"",$res->details))),80) !!}', // 分享描述
            link: '{{route('article_details',['id'=>$res->id])}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{$res->pic}}', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
				$.get("{{route('article_share',['id'=>$res->id])}}",function (ret) {

                })
            }
        });
        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '{{$res->title}}', // 分享标题
            link: '{{route('article_details',['id'=>$res->id])}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{$res->pic}}', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                $.get("{{route('article_share',['id'=>$res->id])}}",function (ret) {

                })
            }
        });
    });
	@includeWhen($res->type == 3, 'index.public._audi')
</script>
</html>