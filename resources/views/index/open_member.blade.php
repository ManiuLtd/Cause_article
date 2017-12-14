<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>开通谁查看我功能</title>
	@include('index.public.css')
</head>
<body>
<div id="open" class="flexv wrap">
	<div class="flexitemv mainbox" style="background: #f7403c;">
		@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($shiptime->membership_time)))
			<div class="state">状态：<span>未开通</span></div>
		@else
			<div class="state">有效期至：<span>{{\Carbon\Carbon::parse($shiptime->membership_time)->toDateString()}}</span></div>
		@endif
		<div class="bjimg">
			<div class="userimg">
				<img src="/kf_login.png" class="fitimg">
			</div>
			<div class="option">
				<div class="discount">
					<div class="flex sale">
						<div class="flexv">
							<div class="price">
								<span>12个月</span>
								<em>&yen; 99</em>
							</div>
							<div class="cost">
								<i class="original">原价 199元</i>
								<span>立省100元</span>
							</div>
							<div class="flex center cornu">优惠</div>
						</div>
						<div class="flexitem endh ">
							<a href="javascript:;" class="flex center discounts" data-price="99" data-type="2" data-uid="{{session()->get('user_id')}}" data-title="爆文12个月会员">优惠抢购</a>
						</div>
					</div>
					<div class="flex month">
						<div class="flexv center price">
							<span>1个月</span>
							<em>&yen; 19.9</em>
						</div>
						<div class="flexitem endh">
							<a href="javascript:;" class="flex center discounts" data-price="19.9" data-type="1" data-uid="{{session()->get('user_id')}}" data-title="爆文1个月会员">开通</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="flex center once">
		<a href="javascript:;" class="flex center">立即开通</a>
	</div>
	<!--弹框-->
	<div class="flex center hint">
		<div class="mask"></div>
		<div class='content'>
			<h3 class="flex center">开通成功</h3>
			<div class="img">
				<img src="/index/image/dredge_bj.png" class="fitimg">
			</div>
			<div class="flex center indate">
				<div>有效期至：</div>
				<span>2017-09-15 10:42:20</span>
			</div>
			<div class="button">
				<a href="{{route('read_share')}}" class="flex center">去看看谁查看了我的头条</a>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript" src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    //  点击回到底部
    var h = $(".bjimg").height()-$(document).height()+$(".state").innerHeight();
    $(".once a").click(function () {
        $(".mainbox").animate({ scrollTop: h }, 200);
    });
    $(".mainbox").scroll(function () {
        boxh =  $(".mainbox").scrollTop();
        if(boxh >= h-20){
            $(".once").hide();
        }else{
            $(".once").show();
        }
    });

	var time;
	@if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($shiptime->membership_time)))
	time = moment("{{date('Y-m-d H:i:s',time())}}","YYYY-MM-DD HH:mm:ss");
	@else
	time = moment("{{$shiptime->membership_time}}","YYYY-MM-DD HH:mm:ss");
	@endif
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$package['appId']}}', // 必填，公众号的唯一标识
        timestamp: {{$package['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: '{{$package['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$package['signature']}}',// 必填，签名，见附录1
        jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.error(function(res){
        alert(JSON.stringify(res));
    });
	$('.discounts').click(function () {
	    var price = $(this).attr('data-price'),
			type  = $(this).attr('data-type'),
			uid   = $(this).attr('data-uid'),
			title = $(this).attr('data-title');
		$.post("{{route('submit_order')}}",{uid:uid,title:title,price:price,type:type,_token:"{{csrf_token()}}"},function (ret) {
		    if (ret.state == 0){
                pay(ret.data,function () {
                    if(type == 1){
                        var newtime = time.add(1, 'months')
					}else{
                        var newtime = time.add(12, 'months')
					}
                    $(".indate span").text(newtime.format("YYYY-MM-D k:mm:ss"));
                    $(".hint").css({"display":"block"});
                    $(".hint").find(".content").addClass('trans');
                });
			} else {
		        showMsg('支付出错啦');
			}
        });
        var pay = function ($config,callback) {
            wx.chooseWXPay({
                timestamp:  $config['timestamp'] ,
                nonceStr: $config.nonceStr,
                package: $config.package,
                signType: $config.signType,
                paySign: $config.paySign, // 支付签名
                success: function (res) {
                    // 支付成功后的回调函数
                    callback && callback(res);
                }, cancel: function (){
                    showMsg('取消了支付');
                }, error: function (res){
                    alert(JSON.stringify(res));
                }
            });
        };
    });

    $(".mask").click(function(){
        $(".hint").css({"display":"none"});
        window.location.reload();
    });
</script>
</html>