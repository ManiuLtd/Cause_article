<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>推广中心</title>
    @include('index.public.css')
</head>
<body>
    <div id="expand" class="twinkle flexv wrap">
        <div class="flexitemv datum">
            <div class="flexv center sub">
                <a href="{{ route('extension_detail') }}" class="flex center num" style="color: red;text-decoration:underline">{{ $today_integral }}</a>
                <h2 class="flex center income" style="color: red">今日收益</h2>
                <div class="flexv centerv front">
                    <div class="flexitemv center myfront">
                        <em class="flex">{{ $use_integral }}</em>
                        <span class="flex center">已提现</span>
                    </div>
                    <div class="flexitemv center myfront">
                        <em class="flex">{{ $nu_integral }}</em>
                        <span class="flex center">可提现</span>
                    </div>
                    <div class="flexitemv center myfront">
                        <em class="flex">{{ $tot_integral }}</em>
                        <span class="flex center">累计收益</span>
                    </div>
                </div>
            </div>

            <div class="flexv func">
                <a href="{{ route('index.apply_cash') }}" class="flexitem centerv tx">
                    <i class="flex center bls bls-tixian" style="background:#3399fd;"></i>
                    <span class="flex center text">申请提现</span>
                </a>
                <a href="{{ route('get_money_record') }}" class="flexitem centerv jl">
                    <i class="flex center bls bls-jilu" style="background:#ea1c61;"></i>
                    <span class="flex center text">提现记录</span>
                </a>
            </div>
        </div>

        <!--底部-->
        @include('index.public.footer')

    </div>
    <!--客服微信-->
    <div class="twinkle flexv kf-qrcode">
        <div class="flex center kf-tit">提现客服</div>
        <div class="flex center kf-img">
            <img src="../logo.jpg" class="fitimg">
        </div>
    </div>

</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script>
    //点击放大二维码
    var state = true;
    $(".kf-qrcode").click(function () {
        if(!state) return;
        $(this).animate({width:'20rem',top:'20%',right:'17%',});
        $(".kf-tit").animate({fontSize:'2.2em'});
        state = false;
    });
    $("#expand").click(function () {
        if(state) return;
        $(".kf-qrcode").animate({top:'64%',right:'0',width:'6rem',});
        $(".kf-tit").animate({fontSize:'1.2em'});
        state = true;
    })
</script>
</html>