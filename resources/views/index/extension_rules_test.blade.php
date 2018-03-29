<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>佣金规则</title>
    @include('index.public.css')
    <link rel="stylesheet" href="/css/index/reset.css">
</head>
<body>
<div id="rules" class="flexv mainbox wrap">
    <a href="javascript:;" class="flex open-linkx extension">
        <div class="flex center header-bar">
            <div class="flex center title-section">
                <div class="title-line"></div>
                <h3 class="flex center header-info">邀好友成功</h3>
            </div>
            <p class="flex center">邀好友成功，拿30%佣金提成</p>
        </div>
    </a>
    <div class="rules_list">
        <h3 class="flex center">赚钱规则</h3>
        <ul>
            <li>
                <div class="flex center rules-num">1</div>
                <div class="rules-info">小伙伴通过你的推广链接开通事业爆文会员服务，你就能拿付费金额的30%佣金奖励，开通的金额越多奖励的金额就越多</div>
            </li>
            <li>
                <div class="flex center rules-num">2</div>
                <div class="rules-info">奖励金额将自动计入您的钱包，所获得的金额都可以提现。</div>
            </li>
            {{--<li class="flex center">--}}
                {{--<button class="flex center btn extension">点击生成个人推广二维码</button>--}}
            {{--</li>--}}
            <li class="between">
                <a href="javascript:;" class="flex center btn extension">点击生成推广二维码</a>
                <a href="javascript:;" data-url="{{ route('index.user', ['ex_user', session('user_id')]) }}" class="flex center btn href">点击复制推广链接</a>
            </li>
        </ul>
    </div>

    <canvas id="myCanvas" style="display: none"></canvas>
    <img src="/poster.jpg" id="background" style="display: none">

    <!--提示-->
    <div class="flex center hint">
        <div class="mask"></div>
        <div class="content">
            <div class="flex center">邀请海报已通过微信消息发送给你</div>
            <a href="javascript:;" class="flex center" id="close">请去公众号查看</a>
        </div>
    </div>
</div>

<!--推广链接提示框-->
<div id="wechat" class="flex center alert">
    <div class="new"></div>
    <div class="content wechat">
        <h3 class="flex center">长按复制链接</h3>
        <p class="flex center cont"></p>
    </div>
</div>

</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>
<script type="text/javascript" src="/js/common/functions.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script type="text/javascript">
    $("a.href").click(function () {
        $("#wechat").show();
        $("#wechat").find(".content").addClass('trans');
        $(".wechat p").text($(this).attr('data-url'));
    });

    $(".new").click(function () {
        $(".alert").css({"display":"none"});
    });

    $(".mask").click(function () {
        $(".alert").css({"display":"none"});
        window.location.reload();
    });

    $("#close").click(function () {
        WeixinJSBridge.call('closeWindow');
    });

    $(function () {
        $(".extension").click(_.throttle(function () {
            @if($user->extension_image != '' && \Carbon\Carbon::parse($user->image_at)->addDays(10) > \Carbon\Carbon::now())
                showProgress('正在发送二维码');
                $.post("{{route('inviting')}}",{url:"{{ $user->extension_image }}", type:1, _token:"{{csrf_token()}}"},function (ret) {
                    hideProgress();
                    if(ret.state == 0) {
                        $(".hint").css({"display":"block"});
                        $(".hint").find(".content").addClass('trans');
                    } else {
                        showMsg(ret.errormsg)
                    }
                });
            @else
                showProgress('二维码正在火速制作中！请静待20秒~');
                $.get("{{ route('head_qrcode_base64') }}", function (ret) {
                    //canvas画图
                    var image = document.querySelector('#background');
                    var userimg = new Image();
                    var qrcode = new Image();
                    userimg.src = ret.head;
                    qrcode.src = ret.qrcode;
                    qrcode.onload = function (ev) {
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
                        ctx.fillText("我是{{ \Session::get('nickname') }}",image.width/2,180);
                        ctx.arc(userBorderX,userBorderY,userBorderSize,0,2*Math.PI);
                        ctx.stroke();
                        ctx.clip();
                        ctx.drawImage(userimg, 0, 0, userimg.width, userimg.height, userBorderX - userBorderSize, userBorderY - userBorderSize, userBorderSize*2, userBorderSize*2);
                        ctx.restore();
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
                    };
                });
            @endif
        }, 5000, { 'trailing': false }));
    });
</script>
</html>