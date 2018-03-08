@foreach($lists as $list)
    <li class="between">
        <img class="flex u-avatar" src="{{ $list->head }}">
        <div class="flexitemv reg-info">
            <div class="flex centerv reg-title">您邀请的: <strong>{{ $list->wc_nickname }}</strong> 推广成功</div>
            <div class="flex centerv reg-date">{{ $list->extension_at }}</div>
        </div>
    </li>
@endforeach