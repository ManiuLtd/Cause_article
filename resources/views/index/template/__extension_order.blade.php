@foreach($lists as $list)
    <li class="around">
        <img class="flex u-avatar" src="{{ $list['user']['head'] }}">
        <div class="flexitemv reg-info">
            <div class="flex centerv reg-title">您邀请的: <strong class="flexv centerv">{{ $list['user']['wc_nickname'] }}</strong> 付款成功</div>
            <div class="between reg-date">
                <div class="date">{{ $list['pay_time'] }}</div>
                <div class="pay-num">&yen;{{ number_format($list['price'], 2) }}</div>
            </div>
        </div>
    </li>
@endforeach