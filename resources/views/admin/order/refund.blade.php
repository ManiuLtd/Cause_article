@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle') ?? '订单退款列表'}} </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="" method="get">
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
                <th>用户昵称</th>
                <th>手机号</th>
                <th>品牌</th>
                <th>到期时间</th>
                <th>订单金额(元)</th>
                <th>会员类型</th>
                <th>订单状态</th>
                <th>退款状态</th>
                <td>备注</td>
                <th>创建时间</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->user->wc_nickname }}</td>
                <td>{{ $value->user->phone }}</td>
                <td>{{ $value->brand_name }}</td>
                <td>{{ $value->user->membership_time }}</td>
                <td>{{ number_format($value->price, 2) }}</td>
                <td>@if($value->type == 1)一个月@else一年@endif</td>
                <td>
                    @if($value->state == 1)
                        <color style="color: green;font-weight: bold;">已支付</color>
                    @elseif($value->state == 2)
                        <color style="color: red;font-weight: bold;">支付失败</color>
                    @else<color style="color: red;font-weight: bold;">未支付@endif</color></td>
                <td>
                    @if($value->refund_state != '' && $value->refund_time != '')
                        <color style="color: red;font-weight: bold;">退款成功</color>
                    @endif
                </td>
                <td>
                    <a class="btn btn-xs btn-info remark" data="{{ $value->remark }}" data-url="{{ route('admin.order_remark', $value->id) }}">
                        @if($value->remark)
                            {{ subtext($value->remark, 5) }}
                        @else
                            <i class="icon-edit bigger-120"></i>
                        @endif
                    </a>
                </td>
                <td>{{ $value->created_at }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center">
            {{$list->appends(['state'=>request()->state,'key'=>request()->key,'value'=>request()->value])->links()}}
        </div>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/admin/layer/layer.js"></script>
<script type="text/javascript" src="/admin/js/bootbox.min.js"></script>
<script type="text/javascript" src="/js/common/functions.js"></script>
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