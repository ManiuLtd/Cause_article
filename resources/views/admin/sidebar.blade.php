<div class="sidebar" id="sidebar">
    <ul class="nav nav-list">
        @foreach($menu as $value)
            @if($value['display'] == 1)
                <li class="@if($value == $active['id'] && $value['pid'] == 0) active @endif @if($value['id'] == $active['pid']) active open @endif">
                    <a href="{{$value['url']}}" class="dropdown-toggle">
                        <i class="{{$value['icon']}}"></i>
                        <span class="menu-text"> {{$value['title']}} </span>
                        @if(!empty($value['children']))<b class="arrow icon-angle-down"></b>@endif
                    </a>
                    @if(!empty($value['children']))
                    <ul class="submenu">
                        @foreach($value['children'] as $child)
                        @if($child['display'] == 1)
                        <li class="@if($child['id'] == $active['id'] || $child['id'] == $active['pid']) active @endif">
                            <a href="{{$child['url']}}" class="dropdown-toggle">
                                <i class="icon-list"></i>
                                {{$child['title']}}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>

    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>

    <script type="text/javascript">
        try {
            ace.settings.check('sidebar', 'collapsed')
        } catch (e) {
        }
    </script>
</div>