<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>最近看我的</title>
    @include('index.public.css')
</head>
<body>
<div id="also" class="flexv wrap">
    <div class='flexitemv mainbox'>
        <div class="between after">
            <div class="flex center kf">
                <img src="{{ $foot->user->head }}" class="img">
                <span class="flex">{{ $foot->user->wc_nickname }}</span>
            </div>
            <div class="time">{{ $foot->created_at->toDateString() }}</div>
        </div>

        <p class="flex center more">看过的文章</p>

        <div class="listbox">
            @foreach($foot_list as $value)
                <div class="flex lists">
                    <div class="img">
                        <img class="fitimg" src="{{ $value->userArticle->article->pic }}">
                    </div>
                    <div class="flexitemv cont">
                        <h2 class="flexitem">{{ $value->userArticle->article->title }}</h2>
                        <div class="between base">
                            <span><em>{{ $value->created_at->toDateString() }}</em></span>
                            <span>阅读时间：{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</span>
                        </div>
                    </div>
                    <a href="{{ route('user_article_details', $value->uaid) }}" class="link"></a>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>