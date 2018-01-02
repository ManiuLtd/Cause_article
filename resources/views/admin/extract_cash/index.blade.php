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
                    <th>ID</th>
                    <th>用户名称</th>
                    <th>用户手机</th>
                    <th>提现金额(元)</th>
                    <th>提现状态</th>
                    <th>备注</th>
                    <th>申请时间</th>
                    <th>完成时间</th>
                    <th>操作</th>
                </tr>
            </thead>

            <tbody>
                @foreach($list as $value)
                    <tr>
                        <td>{{$value->id}}</td>
                        <td>{{$value->user->wc_nickname}}</td>
                        <td>{{$value->user->phone}}</td>
                        <td>{{$value->integral}}</td>
                        <td>
                            @if($value->state == 1)
                                <color style="color: green;font-weight: bold">完成</color>
                            @else
                                <color style="color: red;font-weight: bold">未完成</color>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-xs btn-info remark" data="{{ $value->remark }}" data-url="{{ route('admin.extract_remark', $value->id) }}">
                                @if($value->remark)
                                    {{ subtext($value->remark, 5) }}
                                @else
                                    <i class="icon-edit bigger-120"></i>
                                @endif
                            </a>
                        </td>
                        <td>{{$value->created_at}}</td>
                        <td>{{$value->over_at}}</td>
                        <td>
                            @if($value->state == 0)
                                <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                    <a href="{{ route('admin.extract_complete', $value->id) }}" class="btn btn-xs btn-success">确认完成</a>
                                </div>
                            @endif
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
<script type="text/javascript" src="/admin/js/bootbox.min.js"></script>
<script>
    //添加订单备注
    $('.remark').click(function () {
        var remark = $(this).attr('data'),
            url  = $(this).attr('data-url');
        var content = '备注内容<input class="bootbox-input form-control newremark" type="text" value="' + remark + '"></form>';
        bootbox.dialog({
            title: '填写备注信息：',
            message: content,
            buttons: {
                "success" : {
                    "label" : "备注",
                    "className" : "btn-success",
                    "callback": function() {
                        var newremark = $('.newremark').val();
                        $.post(url, {remark:newremark,_token:"{{ csrf_token() }}"}, function (ret) {
                            console.log(ret);
                            window.location.reload();
                        })
                    }
                },
                "close" : {
                    "label" : "取消",
                    "className" : "btn"
                }
            }
        });
    });
</script>
@endsection