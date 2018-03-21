@foreach($lists as $value)
    <div class="flexv centerv lists">
        <div class="flex top">
            <div class="headimg">
                <img src="{{ $value->user['head'] }}" class="fitimg">
            </div>
            <div class="flexitemv info">
                <p class="flex centerv">
                    @if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($value->user['membership_time'])))
                        {{ str_limit( $value->name, 1 ) }}
                    @else
                        {{ $value->name }}
                    @endif
                </p>
                <p class="flex centerv">
                    @if(\Carbon\Carbon::parse('now')->gt(\Carbon\Carbon::parse($value->user['membership_time'])))
                        {{ substr( $value->phone, 0, 3 ) }}********
                    @else
                        {{ $value->phone }}
                    @endif
                </p>
            </div>
            <div class="flex endh">{{ $value->created_at }}</div>
        </div>
        <a href="{{route('message_detail', $value->id)}}" class="flex center">点击查看留言详情</a>
    </div>
@endforeach