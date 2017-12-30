<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>申请提现</title>
    @include('index.public.css')
    <link rel="stylesheet" href="/plugins/mobile/need/layer.css">
    <style>
        .sl-item {padding: 10px;font-size: 16px;text-align: center;}
        .line {border-bottom: 1px solid #fff;}
    </style>
</head>
<body>
<div id="cash" class="flexv wrap">
    <div class="flexitemv mainbox">
        <div class="cashes">
            <div class="flex center title">推广奖励申请提现</div>
            <div class="flexv bank">
                @if(!$user->user_account)
                    <div class="flex"><span class="flexitem">未绑定账户</span></div>
                    <div class="flex"><em class="flexitem">您当前尚未绑定银行卡请先绑定</em></div>
                    <a href="{{ route('index.bind_account') }}" class="flex endh centerv bls bls-yjt"></a>
                @else
                    <div class="flex"><span class="flexitem">{{ $user->user_account->type }}</span></div>
                    <div class="flex"><em class="flexitem">{{ $user->user_account->name }} {{ $user->user_account->card }}</em></div>
                    <a href="{{ route('index.bind_account') }}" class="flex endh centerv bls bls-yjt"></a>
                @endif
            </div>
            <h2 class="flex center">{{ $nu_integral }}</h2>
            <p class="flex center">当前可提现余额</p>
            <a href="javascript:;" class="flex center getcash @if(!$user->user_account) disable @endif" data-integral="{{ $nu_integral }}" data-url="{{ route('get_money') }}">申请提现</a>
        </div>
        <div class="flex center tips"><span>温馨提示</span></div>
        <div class="tip">
            <p>提现需要注意满足以下条件</p>
            {{--<p>1.提现前需先绑定银行卡(商家中心-个人资料-账户绑定)</p>--}}
            {{--<p>2.提现前需先绑定微信(商家中心-个人资料-微信绑定)</p>--}}
            <p>1.提现金额不可低于100(剩余不足100的年底可申请发放)</p>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/index/js/functions.js"></script>
<script src="/index/js/checkform.js"></script>
<script src="http://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">
    $('#cash .getcash').click(function () {
        if ($(this).hasClass('disabled')) {
            showMsg("请先绑定银行卡", 0);
        }
        var trottle = null,
            post_url = $(this).attr('data-url'),
            integral = $(this).attr('data-integral');
        layer.open({
            content: '<div class="sl"><p class="sl-item line">可使用余额<span class="total-num">'+integral+'</span></p><input type="number" name="integral" value="" placeholder="请输入本次提现余额" class="sl-item"></div>'
            , skin: 'footer'
            , btn: ['确定', '取消']
            , yes: function (index, el) {
                var num = el.querySelector('input[name=integral]').value;
                if(num<=0){
                    showMsg('兑换不能小于1积分');return layer.close(index);
                }else {
                    //异步请求银行卡提现
                    if(!trottle) {
                        $.post(post_url, { integral: num,_token:"{{ csrf_token() }}" }, function(ret){
                            layer.close(index);
                            showMsg(ret.msg, 1);
                            setTimeout(function () {
                                window.location.reload();
                            },1000);
                        });
                        trottle = true;
                    }
                }
            }
            , anim: 'up'
            , success: function (el) {
                var totalNum = Number(el.querySelector('.total-num').innerText),
                    oInput = el.querySelector('input[name=integral]');
                oInput.focus();
                oInput.addEventListener('input', function () {
                    var currentNum = Number(oInput.value);
                    if (currentNum > totalNum) {
                        oInput.blur();
                        oInput.value = totalNum;
                    }
                })
            }
        });
    });
</script>
</html>