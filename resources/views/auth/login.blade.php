<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <title>管理员登录</title>
    <link rel="stylesheet" href="/css/admin/reset.css" />
    <link rel="stylesheet" href="/css/admin/icon.css" />
    <link rel="stylesheet" href="/css/admin/common.css" />
    <link rel="stylesheet" href="/css/admin/main.css" />
</head>
<body>
<div id="login">
    <div class="logo" style="background:url(http://image.wmpian.cn/default/logo.png) center no-repeat;"></div>
    <form method="post" id="signin" action="{{route('admin.login')}}">
        {{csrf_field()}}

        <div class="row">
            <span class="rd rd-user"></span>
            <input type="text" name="account" placeholder="用户名" data-rule="*" data-errmsg="帐号不能空" />
        </div>
        <div class="row">
            <span class="rd rd-password"></span>
            <input type="password" name="password" placeholder="密码" data-rule="*" data-errmsg="登录密码不能空"  />
        </div>
        <div class="row">
            <span class="rd rd-key"></span>
            <input type="text" name="code" placeholder="验证码" class="code" />
            <img src="{{ URL('admin/captcha/1') }}" onclick="this.src='{{ URL('admin/captcha') }}/' + Math.random()" class="codeimg" />
        </div>
        <input type="button" value="登录" class="btn" id="submit" />
    </form>
</div>
<script src="http://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/common/functions.js"></script>
<script src="/js/common/checkform.js"></script>
<script type="text/javascript">
    new checkForm({
        form : '#signin',
        btn : '#submit',
        error : function (ele,err){
            showMsg(err);
        },
        complete : function (ele){
            var url = $(ele).attr('action'),datas = $(ele).serializeArray();
            showProgress('登录中');
            $.post(url,datas,function (ret){
                if(ret.state == 0) {
                    showMsg(ret.msg, 1);
                    if (ret.url) setTimeout(function () {
                        window.location.href = ret.url;
                    }, 1000);
                }else{
                    showMsg(ret.msg)
                }
//                var src = $('.codeimg').attr('src');
//                var code = src.lastIndexOf('?') > -1 ? src.substr(0,src.lastIndexOf('?')) : src;
//                $('.codeimg').attr('src',code + '?' + Math.random());
            },'json');
        }
    });
</script>
</body>
</html>