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
        @foreach($type as $value)
            <a href="{{ route('extension_photo_list', $value->id) }}" class="flex center @if(request()->type) @if($value->id == request()->type) current @endif @else @if($loop->first) current @endif @endif">
                {{ $value->name }}
            </a>
        @endforeach
    </div>
    <div class="flexitemv mainbox">
        <div class="fwrap listbox">
            @foreach($photos as $photo)
                <a href="{{ route('extension_poster', $photo->id) }}" class="flexv imgbox">
                    <div class="img">
                        <img src="/uploads/{{ $photo->url }}" class="fitimg">
                    </div>
                    <div class="flexv center tit">{{ $photo->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">

</script>
</html>