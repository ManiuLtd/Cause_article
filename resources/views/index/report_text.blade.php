<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>举报 / 报错</title>
    @include('index.public.css')
</head>
<body id="text" class="flexv warp">
    <form action="{{ route('report_post') }}" id="form" method="post">
        {{ csrf_field() }}
        <div class="report-text-area">
            {{--<div class="text-num-tip" style="border: 1px #e1e1e1 solid;margin-bottom: 15px;font-size: 1.5rem;">--}}
                {{--<input type="text" name="phone" maxlength="11" placeholder="请填写你的手机号" data-rule="m" data-errmsg="请填写您的手机号">--}}
            {{--</div>--}}
            <div class="text-input-wrap">
                <textarea name="message" id="tip_content" class="text-input" cols="30" rows="10" maxlength="150" placeholder="请详细描述您的问题" data-rule="*" data-errmsg="请详细描述您的问题"></textarea>
                <div class="text-num-tip">
                    <span class="now-num">0</span>/<span class="max-num">150</span>
                </div>
            </div>
            <input type="hidden" name="aid" value="{{ request()->article_id }}">
            <input type="hidden" name="atype" value="{{ request()->atype }}">
            <input type="hidden" name="type" value="{{ request()->type }}">
            <button type="button" id="button-alt">提交</button>
        </div>
    </form>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    //字数统计
    var $textInput = $('.text-input');
    var $nowNumSpan = $('.now-num');
    var timer = null;
    var beforeLen = 0;
    $textInput.on('focus', function(e) {
        timer = window.setInterval(function() {
            var text = $textInput.val();
            var nowLen = text.length;
            if(nowLen !== beforeLen) {
                beforeLen = nowLen;
                $nowNumSpan.text(nowLen)
            }
        }, 200)
    }).on('blur', function() {
        timer && window.clearInterval(timer)
    });

    new checkForm({
        form : '#form',
        btn : '#button-alt',
        error : function (ele,err){showMsg(err);},
        complete : function (ele){
            var url = $(ele).attr('action'),post = new FormData(ele);
            showProgress('正在修改');
            $.ajax({
                url: url,
                type: 'POST',
                data: post,
                contentType: false, // 注意这里应设为false
                processData: false,
                success: function(ret) {
                    hideProgress();
                    if(ret.state == 0){
                        showMsg(ret.errormsg,1);
                        if(ret.url) setTimeout(function () {
                            window.location.href = ret.url;
                        },1000);
                    }else{
                        showMsg(ret.errormsg);
                    }
                },
                error: function (jqXHR) {
                    console.log(JSON.stringify(jqXHR));
                }
            })
        }
    });
</script>
</html>