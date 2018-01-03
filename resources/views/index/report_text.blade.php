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
    <div class="report-text-area">
        <div class="text-input-wrap">
            <textarea name="message" id="tip_content" class="text-input" cols="30" rows="10" maxlength="150" placeholder="请详细描述您的问题（选填）"></textarea>
            <div class="text-num-tip">
                <span class="now-num">0</span>/<span class="max-num">150</span>
            </div>
        </div>
        <button type="button" id="button-alt">提交</button>
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
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


    //提交
    $("#button-alt").on("click", function() {
        var areatext = $("#tip_content").val();
        var $msgShow = $('<p class="tip-msg save-ok-msg"></p>');
        if(areatext == "") {
            $('body').append($msgShow);
            $(".tip-msg").text("请输入反馈信息");
        } else {
            var data = {
                '_token' : "{{ csrf_token() }}",
                'aid' : {{ request()->article_id }},
                'message' : areatext,
                'atype' : "{{ request()->atype }}",
                'type' : "{{ request()->type }}"
            };
            $.post("{{ route('report_post') }}", data, function (ret) {
                if(ret.state == 0){
                    $('body').append($msgShow);
                    $(".tip-msg").text("提交成功，谢谢你的反馈");
                    setTimeout(function () {
                        window.location.href = ret.url;
                    },2000)
                }
            })
        }
    });
    
    timer = setInterval(function() {
        /** 定时器执行的任务 **/
        if(!$(".tip-msg").is(":hidden")) {
            $(".tip-msg").remove();
        }
    }, 3000);
</script>
</html>