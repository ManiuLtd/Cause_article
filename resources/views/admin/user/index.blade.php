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
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>微信昵称</th>
                <th>手机号</th>
                <th>品牌</th>
                <th>从业地区</th>
                <th>推广人/id</th>
                <th>会员到期时间</th>
                <th>推广人数</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->wc_nickname}}</td>
                <td>{{$value->phone}}</td>
                <td>@if($value->brand) {{ $value->brand->name }} @endif</td>
                <td>{{$value->employed_area}}</td>
                <td> {{$value->extension['wc_nickname'].' / '.$value->extension['id']}} </td>
                <td>{{$value->membership_time}}</td>
                <td>{{$value->extension_num}}</td>
                <td>{{$value->created_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'/admin/user'))
                            <a href="{{route('admin.be_dealer',['id'=>$value->id])}}" class="btn btn-xs btn-primary">成为经销商</a>
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
        <div style="text-align: center">
            {{$list->appends(['type'=>request()->type,'key'=>request()->key,'value'=>request()->value])->links()}}
        </div>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/admin/layer/layer.js"></script>
<script>
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

    function set_integral(url,id,token) {
        var content = '<form action=' + url + ' class="form-horizontal" style="margin-top: 20px" method="post">' +
            '<div class="form-group"><label class="col-sm-4 control-label no-padding-right"> 佣金比例： </label>' +
            '{{csrf_field()}}' +
            '<input type="hidden" name="id" value="' + id + '">' +
            '<input type="text" name="scale" class="col-xs-10 col-sm-5"/>' +
            '</div>' +
            '<div class="col-md-offset-1 col-md-9" style="text-align: center">' +
            '<button class="btn btn-info" type="submit">修改</button>' +
            '</form>';
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['440px', '250px'], //宽高
            content: content
        });
    }
</script>
@endsection