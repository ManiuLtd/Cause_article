<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>付费人数</title>
    <link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
    <link rel="stylesheet" href="../index/css/index.css">
</head>
<body>
<div id="pay" class="flexv mainbox wrap box">
    @if($lists->isNotEmpty())
        <ul class="flexv reg-container">
            @foreach($lists as $list)
                <li class="around">
                    <img class="flex u-avatar" src="{{ $list['user']['head'] }}">
                    <div class="flexitemv reg-info">
                        <div class="flex centerv reg-title">您邀请的: <strong class="flexv centerv">{{ $list['user']['wc_nickname'] }}</strong> 付款成功</div>
                        <div class="between reg-date">
                            <div class="date">{{ $list['pay_time'] }}</div>
                            <div class="pay-num">&yen;{{ number_format($list['price'], 2) }}</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <p class="flex center reg-msg">已显示全部</p>
    @else
        <div class="flexitem center void">
            <p class="flex center reg-msg">暂无用户付费</p>
        </div>
    @endif
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">
    // 分页
    var outerBox = '.box',
        innerBox = '.reg-container',
        loadBtn = false,
        page = 1;
    $(outerBox).scroll(function() {
        var h = Math.ceil($(this).scrollTop()),lh = $(this).height(),ih = $(innerBox).height();
        if((h + lh) >= ih) {
            if(loadBtn) return;
            loadBtn = true;
            page++;
            if(page < {{ $users->lastPage() }}) {
                var loadTpl = '<div id="more" class="flex center"><i></i><span>正在加载..</span></div>';
                $(loadTpl).appendTo($(innerBox));
                setTimeout(function () {
                    var url = "{{ route('extension_list', 'order') }}" + "?page=" + page;
                    $.get(url, function (ret) {
                        $(innerBox).append(ret.html);
                        $('#more').remove();
                        loadBtn = false;
                    })
                }, 2000)
            }
        }
    });

</script>
</html>