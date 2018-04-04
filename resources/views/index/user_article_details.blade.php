<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<meta name="referrer" content="never">
	<title>{{$res->article['title']}}</title>
	@include('index.public.css')
	<style>
		.box img{ width: 100% !important; height:auto !important; }
		.box iframe{ width: 100% !important; height:auto !important; }
      	.usercard{margin: 1rem 0;}
		.usercard .head{width: 4rem; height: 4rem; border-radius: 50%; overflow: hidden; padding: 0; margin: 0; background: #fff;}
		.usercard .head img{width: 100%; height: 100%; border-radius: 50%;}
		.usercard .link{margin: 0 2rem; width:7rem; height:2.5rem; background:url(/xz.gif) no-repeat;background-size:contain;}
		.usercard .contact .item:last-child{margin-top: 0.2rem;}
		.usercard .contact .item .icon{width: 20px; height: 20px; font-size:1.6rem;}
		.usercard .contact .item .text{padding: 0 0.2rem; font-size:1.2rem;}
		.usercard .contact .item .btn{width:4rem;height:2rem;border-radius:.5rem;font-size:1rem;color:#fff; background:#0178d6;}
		.usercard .contact .item .wx{background:#4ba601;}
	</style>
</head>
<body>
<div @if($res->article->type == 3) id="listen" @else id="article" @endif class="flexv wrap">
	<div class="flexitemv mainbox contents" @if($res->article->type == 3) style="padding:1.2rem" @endif>
		@if($res->article->type == 3)
			<div class="info">
				<h1>{{ $res->article->title }}</h1>
				<div class="bottom"><span>{{$res->created_at->toDateString()}}</span><a href="javascript:;">{{$res->user['wc_nickname']}}</a></div>
			</div>

			<div class="around consult-box">
				<div class="flex center c-img"><img src="{{$res->user['head']}}" class="radimg"></div>
				<a href="{{ route('chatroom', $res->user->id) }}" class="flex center c-zx"></a>
				<div class="flexv no">
					<div class="between phone">
						<i class="flex center bls bls-shouji" style="color:#0178d6;"></i>
						<div class="flex center number"><span>@if($member_time){{$res->user['phone']}}@else{{substr( $res->user['phone'], 0, 3 )}}********@endif</span></div>
						<a @if($member_time)href="tel:{{$res->user['phone']}}"@else href='javascript:;' id='phone'@endif class="flex center n-btn">打电话</a>
					</div>
					<div class="between wx">
						<i class="flex center bls bls-wx" style="width:1.6rem;color:#4ba601;"></i>
						<div class="flex center number"><span>@if($member_time){{$res->user['phone']}}@else{{substr( $res->user['phone'], 0, 3 )}}********@endif</span></div>
						<a href="javascript:;" class="flex center n-btn book">加微信</a>
					</div>
				</div>
			</div>

            <div class="body-img">
                <img src="http://yun.zx85.net/image/jpeg/5a7baf3e56f9b.jpeg">
				<i class="flex center icon bls bls-play" data-src="{{ json_decode($res->article->audio, true)['src'] }}"></i>
            </div>
            <div class="audio">
                <div class="between">
                    <div class="flexv center a-read">
                        <i class="flex center bls bls-ck"></i>
                        <div class='flex center a-num'><span class="flexv center">{{ $res->read }}</span>阅读</div>
                    </div>
                    <div class="flexv center a-read">
                        <i class="flex center bls bls-kx fond @if(session('user_id') != $res->uid))like @endif"></i>
                        <div class='flex center a-num'><span class="flexv center like-count">{{ $res->like }}</span>喜欢</div>
                    </div>
                </div>
                <div class="flex centerv duration">
                    <span class="flex num start">00:00</span>
                    <div class="flexitem progress"><em></em><span class="flex"></span></div>
                    <span class="flex num end">00:00</span>
                </div>
            </div>
			<div class="content max">
				{!! $res->article->details !!}
			</div>
		@else
			<div class="title max">
				<h2 class="flex">{{$res->article['title']}}</h2>
				<div class="flex subhead">
					<span class="date">{{$res->created_at->toDateString()}}</span>
					<span class="name">{{$res->user['wc_nickname']}}</span>
					<a href="{{route('index.index')}}" class="site">事业头条</a>
				</div>

				<div class="around consult-box">
					<div class="flex center c-img"><img src="{{$res->user['head']}}" class="radimg"></div>
					<a href="{{ route('chatroom', $res->user->id) }}" class="flex center c-zx"></a>
					<div class="flexv no">
						<div class="between phone">
							<i class="flex center bls bls-shouji" style="color:#0178d6;"></i>
							<div class="flex center number"><span>@if($member_time){{$res->user['phone']}}@else{{substr( $res->user['phone'], 0, 3 )}}********@endif</span></div>
							<a @if($member_time)href="tel:{{$res->user['phone']}}"@else href='javascript:;' id='phone'@endif class="flex center n-btn">打电话</a>
						</div>
						<div class="between wx">
							<i class="flex center bls bls-wx" style="width:1.6rem;color:#4ba601;"></i>
							<div class="flex center number"><span>@if($member_time){{$res->user['phone']}}@else{{substr( $res->user['phone'], 0, 3 )}}********@endif</span></div>
							<a href="javascript:;" class="flex center n-btn book">加微信</a>
						</div>
					</div>
				</div>
			</div>

			<div class="content max">
				{!! $res->article['details'] !!}
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
				<img src="{{ $res->user['head'] }}" class="fitimg" style="border-radius: 50%;overflow: hidden;">
			</div>
			<p class="flex center name">{{ str_limit($res->user['wc_nickname'], 10) }}</p>
			<div class="flex centerh mesg">
				<span>{{ $res->user->profession ? $res->user->profession : '健康顾问' }}</span>
				<span>@if($member_time){{ $res->user['phone'] }}@else{{ substr( $res->user['phone'], 0, 3 ) }}********@endif</span>
			</div>
			<div class="buttons">
				<a @if($member_time)href="tel:{{ $res->user['phone'] }}"@else href='javascript:;' id='phone'@endif class="flex center phone">
					<i class="flex center bls bls-dh"></i>
					<span>给我电话</span>
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

		<a href="{{ route('chatroom', $res->user->id) }}" class="flex center bls bls-kefu service"></a>
		
		<div class="flexv center text-box">
			<p>本文为 <span>{{ $res->user['wc_nickname'] }}</span> 发布，不代表事业头条立场</p>
			<p>若内容不规范或涉及违规，可立即 <a href="{{ route('report',['article_id'=>$res->id,'type'=>2]) }}">举报/报错</a></p>
		</div>
	</div>

	@if(session()->get('user_id') != $res->uid)
		<div class="flex center fixed-btn">
			<a href="javascript:;" id="cut" class="flex center cut">免费换成我的名片 >></a>
		</div>
	@endif
	
	<!--提示-->
	<div class="flex center hint">
		<div class="mask"></div>
		<div class='content'>
			<h3 class="flex center">加我免费咨询</h3>
			<div class="qrcode">
				<img src="{{ $res->user->qrcode }}" class="fitimg">
			</div>
			<p class="flex center">长按识别二维码</p>
		</div>
	</div>

	<!--提示-->
	<div class="flex center gzh" style="display: none">
		<div class="mask"></div>
		<div class='content'>
			<h3 class="flex center">更多免费资讯</h3>
			<div class="qrcode">
				<img src="/qrcode.jpg" class="fitimg">
			</div>
			<p class="flex center">长按识别二维码</p>
		</div>
	</div>

	<!--提示-->
	<form class="flex center alert" id="form" action="{{route('perfect_information', session('user_id'))}}">
		{{csrf_field()}}

		<input type="hidden" name="id" value="{{session('user_id')}}">
		<div class="mask"></div>
		<div class='content'>
			<i class="flex center bls bls-cuo cuo"></i>
			<h3 class="flex center title">您的信息不完整</h3>
			<p class="flex center tis" style="font-size: 1.2rem">立刻完善资料，让客户找到您</p>
			<div class="flex center input">
				<span class="flex centerv">姓名</span>
				<input type="text" name="wc_nickname" class="flexitem" value="{{ $user->wc_nickname }}" data-rule="*" data-errmsg="请填写您的姓名">
			</div>
			<div class="flex center input">
				<span class="flex centerv">手机号</span>
				<input type="text" name="phone" class="flexitem" value="" data-rule="m" data-errmsg="手机号码格式错误">
			</div>
			<div class="flex centerv input brands">
				<span class="flex centerv">品牌</span>
				<input type="text" readonly="readonly" class="flexitem cenk" placeholder="选择品牌" value="{{ optional($brand)->name }}" data-rule="*" data-errmsg="请选择您的品牌" onfocus="this.blur()">
				<input type="hidden" name="brand_id" class="brand_id" value="{{ optional($brand)->id }}">
				<i class="flex smtxt"></i>
				<i class="flex center bls bls-xia brand"></i>
			</div>
			<a href="javascript:;" class="flex center button" id="submit">保存修改</a>
		</div>
	</form>
	<!--品牌-->
	<div id="brand" class="flexv dialog_box">
		<div class="flex center head">
			<a href="javascript:;" class="bls bls-zjt"></a>
			<h1 class="flexitem center" style="margin-left: -2rem;">选择品牌</h1>
		</div>
		<ul class="flexitemv mainbox company" style="padding-top: 20px">

		</ul>
		<ul class="flexitemv lettrt">
			<li class="flexitem center"><a href="#">#</a></li>
			<li class="flexitem center"><a href="#A">A</a></li>
			<li class="flexitem center"><a href="#B">B</a></li>
			<li class="flexitem center"><a href="#C">C</a></li>
			<li class="flexitem center"><a href="#D">D</a></li>
			<li class="flexitem center"><a href="#E">E</a></li>
			<li class="flexitem center"><a href="#F">F</a></li>
			<li class="flexitem center"><a href="#G">G</a></li>
			<li class="flexitem center"><a href="#H">H</a></li>
			<li class="flexitem center"><a href="#I">I</a></li>
			<li class="flexitem center"><a href="#J">J</a></li>
			<li class="flexitem center"><a href="#K">K</a></li>
			<li class="flexitem center"><a href="#L">L</a></li>
			<li class="flexitem center"><a href="#M">M</a></li>
			<li class="flexitem center"><a href="#N">N</a></li>
			<li class="flexitem center"><a href="#O">O</a></li>
			<li class="flexitem center"><a href="#P">P</a></li>
			<li class="flexitem center"><a href="#Q">Q</a></li>
			<li class="flexitem center"><a href="#R">R</a></li>
			<li class="flexitem center"><a href="#S">S</a></li>
			<li class="flexitem center"><a href="#T">T</a></li>
			<li class="flexitem center"><a href="#U">U</a></li>
			<li class="flexitem center"><a href="#V">V</a></li>
			<li class="flexitem center"><a href="#W">W</a></li>
			<li class="flexitem center"><a href="#X">X</a></li>
			<li class="flexitem center"><a href="#Y">Y</a></li>
			<li><a href="#Z">Z</a></li>
		</ul>
	</div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.15/clipboard.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    // 喜欢
    $(".fond").click(function () {
        if($(this).hasClass('bls-sx')) return false;
        $(this).removeClass('bls-kx').addClass('bls-sx').css('color','#f13c50');
        var up = '<em class="flex center add">+1</em>';
        $(this).parent().append(up);
        $("em.add").animate({top:'-1.2rem',opacity:'.5'},1000,function () {
            var like = $('.like-count'),
                url = "{{ route('article_like', [$res->id, 2]) }}";
            $.get(url, function (ret) {
                like.html(Number(like.html()) + Number(1));
            });
            $("em.add").remove();
        });
    });

    $('#cut').click(function () {
		@if(!$user->brand_id && !$user->phone)
			$(".alert").css({"display":"block"});
			$(".alert").find(".content").addClass('trans');
			//  品牌
			@include('index.public._brand_list')
		@else
			window.location.href = "{{ route('become_my_article',[$res->article['id'], $res->uid]) }}";
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
							window.location.href = "{{ route('become_my_article',['user_id'=>session('user_id'),'article_id'=>$res->article['id'],'pid'=>$res->uid]) }}";
						}, 2000);
					} else {
						showMsg('完善资料失败');
					}
				},'json');
			}
		});
    @endif

    //  展示全部
    $(".unfold").click(function () {
        $(".content").removeClass('max');
        $(this).remove();
    });

    $('#phone').click(_.throttle(function () {
        showMsg('该用户未开通此服务');
        {{--$.get("{{ route('tip_user_qrcode', $res->user->id) }}", function () {});--}}
    }, 3000, { 'trailing': false }));

	//	加微信
	@if(\Carbon\Carbon::parse($res->user->membership_time)->gt(\Carbon\Carbon::now()))
		@if($res->user->qrcode)
			$(".book").click(function () {
				$(".hint").css({"display":"block"});
				$(".hint").find(".content").addClass('trans');
			});
		@else
			@if($res->uid == session('user_id'))
				$(".book").click(function () {
					showMsg('您尚未上传二维码', 0, 1500);
				});
			@else
				$(".book").click(function () {
					showMsg('该用户尚未上传二维码', 0, 1500);
                	$.get("{{ route('tip_user_qrcode', $res->user->id) }}", function () {});
				});
			@endif
		@endif
        $(".mask").click(function(){
            $(".hint").css({"display":"none"});
        });
	@else
		$(".book").click(_.throttle(function () {
			showMsg('该用户未开通此服务');
		}, 3000, { 'trailing': false }));
	@endif

    $(".mask").click(function(){
        $(".gzh").hide();
    });

	//上传个人二维码
	$("#put").change(function (event) {
		var file = event.target.files[0];
		if(file){
			var reader = new FileReader();
			reader.onload=function (event) {
				var image = event.target.result;
                $(".qrcode .img img").attr('src',image);
				$.post("{{route('upload_qrcode')}}",{url:image, _token:"{{csrf_token()}}"}, function (ret) {
					if(ret.state == 0){
					    showMsg(ret.errormsg, 1);
					} else {
					    showMsg(ret.errormsg);
					}
                });
			};
			reader.readAsDataURL(file);
		}
	});

    wx.config(<?php echo $js->config(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) ?>);

//----------------记录阅读时间-------------------
logid = "{{ $footid }}";
logTime();

function logTime() {
    if (logid) {
        //1-5随机数
        var v_random = Math.floor(Math.random() * 5 + 1);
        var c_random = Math.floor(Math.random() * 29 + 1);
        //调用函数
        function logReadTime(time, random) {
			$.ajax({
				url: "{{route('user_article_time')}}",
				data: {
                    id: logid,
                    time: time + random,
					_token:"{{csrf_token()}}"
                },
				dataType: "json",
				type: "post",
				success: function (res) {

				},
				error: function () {}
			});
        }

        function setTime(second) {
            var a5 = setInterval(function () {
                logReadTime(second, v_random);
            }, second * 1000);
            setInterval(function () {
                clearInterval(a5);
            }, second * 1000 + 10);
        }

        function setTime_later(second) {
            var a6 = setInterval(function () {
                logReadTime(second, c_random);
            }, second * 1000);
            setInterval(function () {
                clearInterval(a6);
            }, second * 1000 + 10);
        }
        //-------1分钟前记录--------
        for (var i = 5; i < 61; i++) {
            setTime(i);
            i = i + 4;
        }
        //-------1分钟后记录--------
        for (var j = 90; j < 601; j++) {
            setTime_later(j);
            j = j + 29;
        }
    }
}
/*******************/
	@if(strstr($res->user['head'],'http'))
	var head = "{{ $res->user['head'] }}";
	@else
	var head = 'http://bw.eyooh.com{{ $res->user['head'] }}';
	@endif
    wx.ready(function(){
        //分享微信好友
        wx.onMenuShareAppMessage({
            title: '{{ $res->article['title'] }}', // 分享标题1
            desc: '我是事业顾问{{ $res->user->wc_nickname }}，分享事业传播健康！这篇文章很不错，请您阅读。', // 分享描述
            link: '{{route('user_article_details',['id'=>$res->id, 'ex_id'=>session('user_id')])}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                $.get("{{route('user_article_share',['id'=>$res->id, 'ex_id'=>session('user_id')])}}",function (ret) {

                });
				$('.gzh').show();
				return false;
            }
        });

        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '{{$res->article['title']}}', // 分享标题
            link: '{{route('user_article_details',['id'=>$res->id, 'ex_id'=>session('user_id')])}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: head, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                $.get("{{route('user_article_share',['id'=>$res->id, 'ex_id'=>session('user_id')])}}",function (ret) {

                });
                $('.gzh').show();
                return false;
            }
        });
    });

	@includeWhen($res->article->type == 3, 'index.public._audi')
</script>
</html>