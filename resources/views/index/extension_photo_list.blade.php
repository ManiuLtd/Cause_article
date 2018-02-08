<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>美图列表</title>
    @include('index.public.css')
</head>
<body>
<div id="more" class="flexv wrap">
    <div class="flex tex-t">
        @if($user->brand_id)
            <a href="{{ route('extension_photo_list') }}" class="flex center @if(!request()->type) current @endif">
                <span class="flex center">{{ $user->brand->name }}</span>
            </a>
            @foreach($types as $value)
                <a href="{{ route('extension_photo_list', $value->id) }}" class="flex center @if(request()->type) @if($value->id == request()->type) current @endif @endif">
                    <span class="flex center">{{ $value->name }}</span>
                </a>
            @endforeach
        @else
            @foreach($types as $value)
                <a href="{{ route('extension_photo_list', $value->id) }}" class="flex center @if(request()->type) @if($value->id == request()->type) current @endif @else @if($loop->first) current @endif @endif">
                    <span class="flex center">{{ $value->name }}</span>
                </a>
            @endforeach
        @endif


    </div>
    <div class="flexitemv mainbox">
        <div class="fwrap listbox">
            @foreach($photos as $photo)
                <a href="{{ route('extension_poster', $photo->id) }}" class="flexv imgbox">
                    <div class="flex center img">
                        <img data-original="{{ $photo->url }}" src="/index/image/loading.gif" class="lazy">
                    </div>
                    <div class="flexv center tit">{{ $photo->name }}</div>
                </a>
            @endforeach
        </div>
        <div class="flex center loding hide">加载中......</div>
        <div class="flex center ending" style="display: none">已全部加载</div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/index/js/lazyload.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script type="text/javascript">
    $(".lazy").lazyload({
        event: "scrollstop",
        effect : "fadeIn",
        container: $(".fwrap.listbox"),
        load:function ($e) {
            $e.css({"width":"100%","height":"100%"});
        }
    });

    // 简单的防抖动函数
    function debounce(func, wait) {
        // 定时器变量
        var timeout;
        return function() {
            // 每次触发 scroll handler 时先清除定时器
            clearTimeout(timeout);
            // 指定 xx ms 后触发真正想进行的操作 handler
            timeout = setTimeout(func, wait);
        };
    };
    // 实际想绑定在 scroll 事件上的 handler
    function realFunc(){
        var scrollTop = Math.ceil(scroll.scrollTop()),thisHeight = scroll.height(),boxHeight = $(".listbox").height();
        console.log(scrollTop, thisHeight, boxHeight);
        if((scrollTop + thisHeight) == boxHeight) {
            page++;
            if(page < {{ $photos->lastPage() }}) {
                $(".loding").removeClass("hide");
                var url = "{{ route('extension_photo_list', request()->type) }}" + "?page=" + page;
                $.get(url, function (ret) {
                    $(".listbox").append(ret.html);
                    $(".loding").addClass("hide");
                    $(".lazy").lazyload({
                        event: "scrollstop",
                        effect : "fadeIn",
                        container: $(".fwrap.listbox"),
                        load:function ($e) {
                            $e.css({"width":"100%","height":"100%"});
                        }
                    });
                });
            } else {
                $(".ending").removeClass("hide");
            }
        }
    }
    // 采用了防抖动
    var page = 1;
    var scroll = $(".mainbox");
    $(".mainbox").scroll(debounce(realFunc,100));

</script>
</html>