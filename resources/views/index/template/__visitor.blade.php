@foreach($footprint as $value)
    <div class="sharer">
        <div class="between info">
            <div class="flex centerv kf">
                <div class="flex center img">
                    <img src="{{ $value->user->head }}" class="fitimg">
                </div>
                <div class="flexv centerh text">
                    <div class="flexv tex">{{ $value->user->wc_nickname }}</div>
                    <div class="data">
                        <span>{{ date('Y-m-d H:s', strtotime($value->created_at)) }}</span>
                    </div>
                </div>
            </div>
            <div class="flexv center time">
                @if($value->type == 1)
                    <span class="flex"><em>{{\Carbon\Carbon::now()->subSecond($value->residence_time)->diffForHumans(null, true)}}</em></span>
                    <span class="flex">阅读时间</span>
                @else
                    <span style="font-size: 1.4rem;color: red">分享朋友</span>
                @endif
            </div>
            <a href="{{ route('visitor_record_see', $value->id) }}" class="flex center also-btn">他还看了</a>
        </div>
        <div class="relation">
            <p class="text">通过以下人脉关系链接传到-{{ $value->user->wc_nickname }}</p>
            <div class="flex box">
                <div class="flexv center img">
                    <img class="flex" src="{{ $res->user->head }}">
                    <span class="flexv">{{ $res->user->wc_nickname }}</span>
                </div>
                @if(count($value->extension))
                    @foreach($value->extension as $user)
                        <i class="flex centerh bls bls-right"></i>
                        <div class="flexv center img">
                            <img class="flex" src="{{ $user['user']['head'] }}">
                            <span class="flexv">{{ $user['user']['wc_nickname'] }}</span>
                        </div>
                    @endforeach
                @endif
                <i class="flex centerh bls bls-right"></i>
                <div class="flexv center img">
                    <img class="flex" src="{{ $value->user->head }}">
                    <span class="flexv">{{ $value->user->wc_nickname }}</span>
                </div>
            </div>
        </div>
    </div>
@endforeach