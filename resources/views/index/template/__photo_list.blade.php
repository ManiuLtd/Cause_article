@foreach($photos as $photo)
    <a href="{{ route('extension_poster', $photo->id) }}" class="flexv imgbox">
        <div class="flex center img">
            <img src="{{ $photo->url }}" class="lazy" width="100%" height="100%">
        </div>
        <div class="flexv center tit">{{ $photo->name }}</div>
    </a>
@endforeach