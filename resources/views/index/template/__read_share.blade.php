@foreach($lists as $value)
    <div class="listbox">
        <div class="flex centerv top">
            <div class="headimg">
                <img src="{{ $value->user->head }}" class="fitimg">
            </div>
            <div class="flexitemv info">
                <p class="flex centerv">{{ $value->user->wc_nickname }}</p>
                <p class="flex centerv">{{ \Carbon\Carbon::parse($value->created_at)->toDateString() }}</p>
            </div>
            <div class="flex center">停留<em>{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</em></div>
        </div>
        <div class="flex lists">
            <div class="img">
                <img class="fitimg" src="{{ $value->userArticle->article->pic }}"/>
            </div>
            <div class="flexitemv cont">
                <a href="{{route('visitor_details',['id'=>$value['uaid']])}}" class="flexitemv">{{ $value->userArticle->article->title }}</a>
            </div>
        </div>
    </div>
@endforeach