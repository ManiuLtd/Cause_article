@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="{{route('admin.user')}}" method="get">
        <select class="form-control" name="type" style="width: 100px">
            <option value="0" @if(request()->type == 0) selected @endif>用户类型</option>
            <option value="1" @if(request()->type == 1) selected @endif>普通用户</option>
            <option value="2" @if(request()->type == 2) selected @endif>经销商</option>
        </select>
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
                <th>从业地区</th>
                <th>用户类型</th>
                <th>推广人/id</th>
                <th>经销商/id</th>
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
                <td>{{$value->employed_area}}</td>
                <td>@if($value->type == 1) 普通用户 @elseif($value->type == 2) 经销商 @endif</td>
                <td> {{$value->extension['wc_nickname'].' / '.$value->extension['id']}} </td>
                <td> {{$value->dealer['wc_nickname'].' / '.$value->dealer['id']}} </td>
                <td>{{$value->membership_time}}</td>
                <td>{{$value->extension_num}}</td>
                <td>{{$value->created_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if($value->type == 1)
                            @if(has_menu($menu,'/admin/user'))
                                <a href="{{route('admin.be_dealer',['id'=>$value->id])}}" class="btn btn-xs btn-primary">成为经销商</a>
                            @endif
                            @else
                            <a class="btn btn-xs btn-info" onclick="see_commis('{{ route('see_integral',['id'=>$value->id]) }}');">查看推广金</a>
                            <a class="btn btn-xs btn-info" onclick="set_integral('{{ route('set_integral') }}',{{$value->id}},'{{csrf_token()}}');">佣金比例设置</a>
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
    function see_commis(url) {
        $.get(url, function (ret) {
            var content = '<form class="form-horizontal" style="margin-top: 20px">' +
                '<div class="form-group"><label class="col-sm-5 control-label no-padding-right"> 历史推广佣金 </label>' +
                '<label class="col-sm-5 control-label no-padding-left"> ' + ret.history + '元 </label>' +
                '</div>' +
//                '<div class="form-group"><label class="col-sm-5 control-label no-padding-right"> 可用推广佣金 </label>' +
//                '<label class="col-sm-5 control-label no-padding-left"> ' + 1 + '元 </label>' +
//                '</div>' +
                '</form>';
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['340px', '370px'], //宽高
                content: content
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