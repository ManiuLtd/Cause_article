<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>提现记录</title>
    @include('index.public.css')
</head>
<body>
<div id="record" class="flexv wrap">
    <div class="flexitemv mainbox">
        <div class="flexitemv center hide" id="none">
            <i class="bls bls-date"></i>
            <p>暂无相关记录~</p>
        </div>
        <div class="flexitemv date">
            @foreach($lists as $list)
                <div class="between nape">
                    <div class="flexv info">
                        <h2 class="flex centerv">推广提现</h2>
                        <p class="flex centerv time">{{ $list->created_at }}</p>
                    </div>
                    <div class="flexv end right">
                        <h2>&yen;{{ $list->integral }}</h2>
                        <p class="flex center @if($list->state == 0) state @endif">
                            @if($list->state == 1)
                                已完成
                            @else
                                未完成
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>