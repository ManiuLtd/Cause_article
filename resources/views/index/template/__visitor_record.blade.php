@foreach($list as $value)
    <div class="listbox">
        <div class="flex lists">
            <div class="img">
                <img class="fitimg" src="{{ $value['article']['pic'] }}"/>
            </div>
            <div class="flexitemv cont">
                <h2 class="flexitemv">{{ $value['article']['title'] }}</h2>
                <div class="between base">
                    <span><em>{{ \Carbon\Carbon::parse($value['updated_at'])->toDateString() }}</em></span>
                    <span><em>{{ $value['read'] }}</em>浏览</span>
                    <span class="flex center"><em>{{ $value['user_count'] < 99 ? $value['user_count'] : 99}}</em></span>
                </div>
            </div>
            <a href="{{ route('user_article_details', $value['id']) }}" class="link"></a>
        </div>
        <div class="flex details">

            <div class="flex center imgbox">
                @if(\Carbon\Carbon::parse($user->membership_time)->gt(\Carbon\Carbon::parse('now')))
                    @foreach($value['user'] as $users)
                        <div class="flex center userimg"><img src="{{ $users['user_list']['head'] }}" class="fitimg"></div>
                    @endforeach
                @else
                    @foreach($value['user'] as $key => $users)
                        @if($key == 0)
                            <div class="flex center userimg"><svg class="sc" aria-hidden="true"><use xlink:href="#sc-gr"></use></svg></div>
                        @elseif($key == 1)
                            <div class="flex center userimg"><svg class="sc" aria-hidden="true"><use xlink:href="#sc-gr1"></use></svg></div>
                        @elseif($key == 2)
                            <div class="flex center userimg"><svg class="sc" aria-hidden="true"><use xlink:href="#sc-gr2"></use></svg></div>
                        @else
                            <div class="flex center userimg"><svg class="sc" aria-hidden="true"><use xlink:href="#sc-gr"></use></svg></div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="flexitem endh lock">
                <a href="{{ route('visitor_details', $value['id']) }}" class="flex center">谁看了？</a>
            </div>
        </div>
    </div>
@endforeach