@foreach($photo as $value)
    <li class="item">
        <a href="{{ route('extension_poster', $value->id) }}" class="flex link">
            <img src="{{ $value->url }}" class="fitimg">
        </a>
        <p class="flex center name">{{ $value->name }}</p>
    </li>
@endforeach