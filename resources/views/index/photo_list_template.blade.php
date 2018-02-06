@foreach($photos as $photo)
    <a href="{{ route('extension_poster', $photo->id) }}" class="flexv imgbox">
        <div class="flex center img">
            <img data-original="{{ $photo->url }}" src="/index/image/loading.gif" class="lazy">
        </div>
        <div class="flexv center tit">{{ $photo->name }}</div>
    </a>
@endforeach