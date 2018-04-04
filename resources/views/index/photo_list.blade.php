<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>美图列表</title>
    @include('index.public.css')
    <style>
        @if($photos->isEmpty())
        #listbox{position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);}
        @endif
    </style>
</head>
<body>
<div id="more" class="flexv wrap" style="height: 100%">
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
    <div class="flexitemv mainbox mescroll" id="mescroll">
        <div class="fwrap listbox" id="listbox">

        </div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/index/js/lazyload.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'mescroll', 'tip' => '该品牌暂无美图', 'html' => 'listbox', 'route' => route('extension_photo_list', request()->type), 'lists' => $photos, 'lazyload' => 1])

<script type="text/javascript">
    $(".lazy").lazyload({
        event: "scrollstop",
        effect : "fadeIn",
        container: $(".fwrap.listbox"),
        load:function ($e) {
            $e.css({"width":"100%","height":"100%"});
        }
    });
</script>
</html>