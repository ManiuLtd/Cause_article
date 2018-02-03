<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>准客户</title>
    @include('index.public.css')
</head>
<body>
<div id="prospect" class="flexv wrap">
    <div class='flexitemv mainbox'>
        @foreach($prospect as $value)
            <div class="afterbox">
                <div class="between after">
                    <div class="flex center kf">
                        <img src="{{ $value->user->head }}" class="img">
                        <span class="flex">{{ $value->user->wc_nickname }}</span>
                    </div>
                    <div class="flexv center data">
                        <span class="flex">{{ $value->created_at->toTimeString() }}</span>
                        <span class="flex">{{ $value->created_at->toDateString() }}</span>
                    </div>
                </div>
                <div class="between text">
                    <span>看过的文章</span>
                    <a href="{{ route('visitor_record_see', $value->id) }}">全部浏览记录>></a>
                </div>

                <div class="listbox">
                    <div class="flex lists">
                        <div class="img">
                            <img class="fitimg" src="{{ $value->userArticle->article->pic }}">
                        </div>
                        <div class="flexitemv cont">
                            <h2 class="flexitem">{{ $value->userArticle->article->title }}</h2>
                            <div class="between base">
                                {{--<span><em>{{ $value->created_at->toDateString() }}</em></span>--}}
                                <span><em>{{ $value->userArticle->read }}</em>浏览</span>
                            </div>
                        </div>
                        <a href="{{ route('user_article_details', $value->uaid) }}" class="link"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>