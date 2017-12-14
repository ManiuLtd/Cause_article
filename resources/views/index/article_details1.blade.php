<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>文章</title>
	<link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
	<link rel="stylesheet" href="/index/css/icon.css">
	<link rel="stylesheet" href="/index/css/index.css">
</head>
<body>
<div id="article" class="flexv wrap">
	<div class="flex center head">
		<a href="javascript:;" class="bls bls-zjt"></a>
		<h1 class="flexitem center">文章</h1>
		<a href="javascript:;" class="bls bls-dian"></a>
	</div>
	
	<div class="flexitemv mainbox content">
		<div class="title">
			<h2 class="flex">{{$res->title}}</h2>
			<div class="flex subhead">
				<span class="date">{{Carbon\Carbon::parse($res->ceated_at)->toDateString()}}</span>
				<span class="name">轩轩</span>
				<a href="javascript:;" class="site">爆单头条</a>
			</div>
			<div>{!! $res->details !!}</div>
		</div>
		
		<div class="flexv centerv unfold">
			<p>展开全文</p>
			<i class="flex center bls bls-xjt"></i>
		</div>
		
		<div class="flexv centerv user">
			<div class="userimg">
				<img src="../image/banner.jpg" class="fitimg">
			</div>
			<p class="flex center name">轩轩</p>
			<div class="flex centerh mesg">
				<span>超级圆桌</span>
				<span>136***4613</span>
			</div>
			<div class="button">
				<a href="" class="flex center phone">
					<i class="flex center bls bls-dh"></i>
					<span>给我电话</span>
				</a>
				<a href="javascript:;" class="flex center phone">
					<i class="flex center bls bls-bd"></i>
					<span>事业宝典</span>
				</a>
			</div>
			<span class="row"></span>
			<span class="col"></span>
			<span class="row last"></span>
			<span class="col last"></span>
		</div>
		
		<div class="flexv center qrcode">
			<div class="img">
				<img src="http://yun.zhzxj.cn/default/user_qrcode_private.jpg" class="fitimg">
			</div>
			<p>马上加我微信沟通</p>
			<a href="javascript:;" class="flex center bls bls-kefu service"></a>
		</div>
		
		<a href="{{route('become_my_article',['uid'=>session()->get('user_id'),'aid'=>$res->id])}}" class="flex center cut">免费换成我的名片 >></a>
		
		<div class="flexv center text">
			<p>本文为<span>轩轩</span>发布，不代表爆单头条立场</p>
			<p>若内容不规范或涉及违规，可立即 <a href="javascript:;">举报/报错</a></p>
			<p class="flex center bottom">&copy;&ensp;2017&ensp;公众号名&ensp;版权所有</p>
		</div>
	</div>
</div>
</body>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$package['appId']}}', // 必填，公众号的唯一标识
        timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$package['signature']}}',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        wx.onMenuShareAppMessage({
            title: '购买视频', // 分享标题
            desc: '说点什么吧', // 分享描述
            link: 'http://www.zhihuizx.cn?pid={$Think.session.user_id}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://www.zhihuizx.cn/gzh.jpg' // 分享图标
        });

        wx.onMenuShareTimeline({
            title: '购买视频', // 分享标题
            link: 'http://www.zhihuizx.cn?pid={$Think.session.user_id}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://www.zhihuizx.cn/gzh.jpg', // 分享图标x
        });
    });
</script>
</html>