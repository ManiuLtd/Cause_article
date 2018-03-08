@foreach($list as $value)
    <a href="{{ route('article_details',['id'=>$value->id]) }}" class="flex lists">
        <div class="img flex center">
            @if(request()->type == 3)
                <img class="lazy" data-original="/index/image/night.jpg" src="/index/image/loading.gif" />
                <i class="flex center bls bls-video"></i>
            @else
                <img class="lazy" data-original="{{ $value->pic }}" src="/index/image/loading.gif" />
            @endif
        </div>
        <div class="flexitemv cont">
            <h2 class="flexv">{{ $value->title }}</h2>
            <div class="flex base">
                <span class="flex center">
                    <i class="flex center bls @if(request()->type == 3) bls-listen @else bls-ck @endif"></i>
                    {{ $value->read }}
                </span>
                <span class="flex center"><i class="flex center bls bls-time"></i>{{ $value->created_at->toDateString() }}</span>
            </div>
        </div>
    </a>
@endforeach