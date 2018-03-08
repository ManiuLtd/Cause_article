@foreach($list as $value)
    <div class="flex lists">
        <div class="img">
            <img class="fitimg" src="{{$value->pic}}"/>
        </div>
        <div class="flexitemv cont">
            <a href="{{route('article_details',['id'=>$value->id])}}" class="flexitemv">{{$value->title}}</a>
            <div class="base">
                <span><em>{{$value->read}}</em>阅读</span>
                <span><em>{{$value->share}}</em>分享</span>
                <span>轩轩</span>
                <span>首创</span>
            </div>
        </div>
    </div>
@endforeach