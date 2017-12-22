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
                <th>从事品牌</th>
                <th>从业地区</th>
                <th>用户类型</th>
                <th>上级经销商/id</th>
                <th>所属部门员工</th>
                <th>员工推广链接</th>
                <th>推广分成</th>
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
                <td>@if($value->type == 2) 经销商 @endif</td>
                <td> {{$value->dealer['wc_nickname'].' / '.$value->dealer['id']}} </td>
                <td>@if($value->admin)<color style="color:green">{{ $value->admin->account }}</color>@endif</td>
                <td>
                    @if($value->admin)
                        <a class="btn btn-xs btn-info" onclick="dealer_url({{ $value->admin_id }});">查看</a>
                    @endif
                </td>
                <td>{{ $value->cmmission }} 元</td>
                <td>{{$value->created_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        <a class="btn btn-xs btn-info" onclick="see_commis('{{ route('see_integral',['id'=>$value->id]) }}');">查看推广金</a>
                        {{--<a class="btn btn-xs btn-info" onclick="set_integral('{{ route('set_integral') }}',{{$value->id}},'{{csrf_token()}}');">佣金比例设置</a>--}}
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

    function dealer_url(id) {
        var url = "{{ config('app.url') }}?become_dealer/"+id;
        var content = '<div class="form-group"><label class="col-sm-2 control-label no-padding-right"> 推广链接： </label>' +
            '<input type="text" class="col-xs-10 col-sm-8" value="' + url + '"/>' +
            '</div>';
        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['540px', '150px'], //宽高
            content: content
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