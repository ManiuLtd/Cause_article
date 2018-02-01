@foreach($photo as $value)
    <li class="item">
        <a href="javascript:;" class="flex link">
            <img src="{{ $value->url }}" class="fitimg">
        </a>
        <p class="flex center name">{{ $value->name }}</p>
    </li>
@endforeach