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
<div id="expand" class="flexv wrap">
    <div class="flexitemv datum">
        <div class="flexv center sub">
            <div class="flex center num">{{ $today_integral }}</div>
            <h2 class="flex center income">今日收益</h2>
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
</body>
</html>