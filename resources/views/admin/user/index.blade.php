@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="{{route('admin.user')}}" method="get">
        <select class="form-control" name="key" style="width: 140px">
            <option value="wc_nickname" @if(request()->key == 'wc_nickname') selected @endif>昵称</option>
            <option value="phone" @if(request()->key == 'phone') selected @endif>手机号</option>
        </select>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
    </form>
    <form action="{{ route('admin_extension') }}" id="form" method="post">
        {{ csrf_field() }}
        <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    @if(has_menu($menu, '/admin/admin_extension'))
                        <th class="center">
                            <label>
                                <input type="checkbox" class="ace"/>
                                <span class="lbl"></span>
                            </label>
                        </th>
                    @endif
                    <th>id</th>
                    <th>微信昵称</th>
                    <th>手机号</th>
                    <th>品牌</th>
                    <th>从业地区</th>
                    <th>推广人/id</th>
                    <th>会员到期时间</th>
                    <th>推广方式</th>
                    <th>推广时间</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody>
                @foreach($list as $value)
                <tr>
                    @if(has_menu($menu, '/admin/admin_extension'))
                        @if(empty($value->admin_id) && empty($value->admin_type))
                            <td class="center">
                                <label>
                                    <input type="checkbox" name="user_id[]" class="ace" value="{{ $value->id }}"/>
                                    <span class="lbl"></span>
                                </label>
                            </td>
                        @else
                            <td class="center">-</td>
                        @endif
                    @endif
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->wc_nickname }}</td>
                    <td>{{ $value->phone }}</td>
                    <td>@if($value->brand) {{ $value->brand->name }} @endif</td>
                    <td>{{$value->employed_area}}</td>
                    <td>{{ $value->extension['wc_nickname'].' / '.$value->extension['id'] }} </td>
                    <td>{{ $value->membership_time }}</td>
                    <td>@if($value->ex_type == 1) 文章 @elseif($value->ex_type == 2) 二维码 @elseif($value->ex_type == 3) 购买页面 @endif</td>
                    <td>{{ $value->extension_at }}</td>
                    <td>{{ $value->created_at }}</td>
                    <td>
                        <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                            @if(has_menu($menu,'/admin/user'))
                                <a href="{{ route('admin.be_dealer',['id'=>$value->id]) }}" class="btn btn-xs btn-primary">成为经销商</a>
                            @endif
                            @if(has_menu($menu,'admin/setMemberTime'))
                                <a href="javascript:;" class="btn btn-xs btn-danger" onclick="setMembertime(this, {{ $value->id }}, '{{date('Y-m-d', strtotime($value->membership_time))}}')" data-url="{{ route('admin.set_member_time') }}">设置会员时间</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @if(has_menu($menu, '/admin/admin_extension'))
                <select name="admin_id">
                    @foreach($admin as $value)
                        <option value="{{ $value->id }}">{{ $value->account }}</option>
                    @endforeach
                </select>
                <a class="btn btn-sm btn-info distribution">分配</a>
            @endif
            <div style="text-align: center">
                {{$list->appends(['type'=>request()->type,'key'=>request()->key,'value'=>request()->value])->links()}}
            </div>
        </div><!-- /.table-responsive -->
    </form>
</div><!-- /span -->
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/admin/layer/layer.js"></script>
<script>
    //提交分配
    $('.distribution').click(function(){
        $('#form').submit();
    });

    function setMembertime(th, id, time) {
        var content = '<form class="form-horizontal" style="margin-top: 20px">' +
            '<div class="form-group">' +
            '<label class="col-sm-3 control-label no-padding-right" style="margin: 4px 10px 0 0"> 会员时间：</label>' +
            '<input type="date" value="'+time+'" class="member-time" >' +
            '</div>' +
            '</form>';
        layer.confirm(content, {
            btn: ['确定','取消'],
            skin: 'layui-layer-rim',
            area: ['370px', '220px']
        }, function(){
            var time = $('.member-time').val(),
                url = $(th).attr('data-url');
            $.post(url, {user_id:id, membership_time:time, _token:"{{ csrf_token() }}"}, function(ret){
                console.log(ret);
                if(ret.state == 0) {
                    layer.msg(ret.error, {icon: 1});
                    setTimeout(function(){
                        window.location.reload();
                    }, 1000)
                }
            });
        });
    }
</script>
@endsection