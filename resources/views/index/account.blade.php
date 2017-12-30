<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>绑定账户</title>
    @include('index.public.css')
</head>
<body>
<div id="account" class="flexv wrap">
    <div class="flexitemv mainbox">
        <form action="{{ route('index.get_code') }}" class="step1" onsubmit="return false">
            {{ csrf_field() }}
            <div class="tips">请绑定持卡本人的银行卡</div>
            <div class="block stepone">
                <label class="flex centerv row select">
                    <span class="flex title">银行/账户</span>
                    <select name="type" class="flexitem" data-rule="*" data-errmsg="请选择银行或账户类型">
                        <option value="">请选择银行或账户类型</option>
                        <option value="支付宝"@if($user->user_account->type == '支付宝')selected @endif>支付宝</option>
                        <option value="工商银行"@if($user->user_account->type == '工商银行')selected @endif>工商银行</option>
                        <option value="农业银行"@if($user->user_account->type == '农业银行')selected @endif>农业银行</option>
                        <option value="建设银行"@if($user->user_account->type == '建设银行')selected @endif>建设银行</option>
                        <option value="交通银行"@if($user->user_account->type == '交通银行')selected @endif>交通银行</option>
                        <option value="招商银行"@if($user->user_account->type == '招商银行')selected @endif>招商银行</option>
                    </select>
                    <i class="flex center bls bls-yjt"></i>
                </label>
                <label class="flex centerv row">
                    <span class="flex title">开户姓名</span>
                    <input type="text" name="name" placeholder="请填写您的开户姓名/真实姓名" class="flexitem input normal" value="{{ $user->user_account->name }}" data-rule="cname" data-errmsg="请填写您的开户姓名/真实姓名">
                </label>
                <label class="flex centerv row">
                    <span class="flex title">卡号/账户</span>
                    <input type="text" name="card" placeholder="请填写您的银行卡/账户" class="flexitem input normal" value="{{ $user->user_account->card }}" data-rule="*" data-errmsg="请填写您的银行卡/账户">
                </label>
            </div>
            <a href="javascript:;" class="flex center submit next">下一步</a>
        </form>
        <form action="{{ route('index.checkCode') }}" class="step2" onsubmit="return false" style="display: none">
            {{ csrf_field() }}
            <input type="hidden" name="user_id" value="{{ $user->user_account->user_id }}">

            <div class="tips">系统已给本账号用户手机发送了短信验证，请输入验证码之后提交。</div>
            <div class="block">
                <label class="flex centerv row">
                    <span class="flex title">卡号/账户</span>
                    <input type="number" name="code" placeholder="短信验证码" class="flexitem input normal" maxlength="6" data-rule="/^\d{6}$/" data-errmsg="验证码错误">
                    <a href="javascript:;" class="flex center getCode">获取验证码</a>
                </label>
            </div>
            <a href="javascript:;" class="flex center submit complete">提交绑定</a>
        </form>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
<script src="/index/js/functions.js"></script>
<script src="/index/js/contact.js"></script>
<script type="text/javascript">
    var step2 = $('.step2');
    var step1 = $('.step1');
    var getCode = $('.getCode');

    new checkForm({
        form: '.step1',
        btn: '.next',
        error: function (obj, msg) {
            showMsg(msg);
        },
        complete: function (form) {
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            $.post(url,datas,function(ret){
                if(ret.state == 0) {
                    showMsg(ret.msg, 1);
                    step1.hide();
                    step2.show();
                    smsTimer(getCode, '重新获取', 60, 'disabled');
                } else{
                    showMsg(ret.msg);
                }
            },'json');
        }
    });

    new checkForm({
        form: '.step2',
        btn: '.complete',
        error: function (obj, msg) {
            showMsg(msg);
        },
        complete: function (form) {
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            if(typeof(editor) == 'object' && editor.html()) datas[datas.length - 1] = {name:'content',value:editor.html()};
            $.post(url,datas,function(ret){
                showMsg(ret.msg, 1);
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            },'json');
        }
    });

    getCode.click(function () {
        if (!$(this).hasClass('disabled')) {
            // smsTimer(getCode, '重新获取', 60, 'disabled');
            //发送验证码
            var url = $('.step1').attr('action');
            $.post(url, {again:1,_token:"{{ csrf_token() }}"}, function (ret) {
                showMsg(ret.msg, 1);
                smsTimer(getCode, '重新获取', 60, 'disabled');
            })
        }
    });
</script>
</html>