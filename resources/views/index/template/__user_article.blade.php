@foreach($list as $value)
    <a href="{{route('user_article_details',['id'=>$value->id])}}" class="flex">
        <div class="flexitem lists">
            <div class="img">
                <img class="fitimg" src="{{$value->article['pic']}}"/>
            </div>
            <div class="flexitemv cont">
                <h2 class="flexv">{{$value->article['title']}}</h2>
                <div class="base">
                    <span><em>{{$value->read}}</em>阅读</span>
                    <span><em>{{$value->share}}</em>分享</span>
                    <span><em>{{$value->created_at->diffForHumans()}}</em></span>
                </div>
            </div>
        </div>
    </a>
@endforeach