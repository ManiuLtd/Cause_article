@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle') ?? '订单总列表'}}  </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="" method="get">
        <select class="form-control" name="key" style="width: 140px">
            <option value="wc_nickname" @if(request()->key == 'wc_nickname') selected @endif>昵称</option>
            <option value="phone" @if(request()->key == 'phone') selected @endif>手机号</option>
        </select>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
    </form>
    <form action="{{ route('order.distribution') }}" id="form" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="type" value="2">
        <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th class="center">
                        <label>
                            <input type="checkbox" class="ace"/>
                            <span class="lbl"></span>
                        </label>
                    </th>
                    <th>ID</th>
                    <th>用户昵称</th>
                    <th>手机号</th>
                    <th>品牌</th>
                    <th>到期时间</th>
                    <th>订单金额(元)</th>
                    <th>会员类型</th>
                    <th>订单状态</th>
                    <th>所属员工</th>
                    <td>备注</td>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody>
                @foreach($list as $value)
                <tr>
                    @if(empty($value->distribution))
                        <td class="center">
                            <label>
                                <input type="checkbox" name="order_id[]" class="ace" value="{{ $value->id }}"/>
                                <span class="lbl"></span>
                            </label>
                        </td>
                        @else
                        <td class="center">-</td>
                    @endif
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->user->wc_nickname }}</td>
                    <td>{{ $value->user->phone }}</td>
                    <td>{{ $value->brand_name }}</td>
                    <td>
                        {{ $value->user->membership_time }} /
                        {{ \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($value->user->membership_time)) ? '到期' : '未到期' }}
                    </td>
                    <td>{{ number_format($value->price, 2) }}</td>
                    <td>@if($value->type == 1)一个月@else一年@endif</td>
                    <td><color style="color: green;font-weight: bold;">已支付</color></td>
                    <td>
                        @if($value->distribution)
                            <color style="color: blue;font-weight: bold;">{{ $value->admin }}</color>
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
                    <td>
                        @if($value->state == 1 && $value->refund_state != 1)
                            <a class="btn btn-xs btn-danger wx-refund" data-url="{{ route('admin.refund', $value->id) }}" data-price="{{ $value->price }}">退款</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <select name="admin_id">
                @foreach($admin as $value)
                <option value="{{ $value->id }}">{{ $value->account }}</option>
                @endforeach
            </select>
            <a class="btn btn-sm btn-info distribution">分配</a>

            <div style="text-align: center">
                {{$list->appends(['state'=>request()->state,'key'=>request()->key,'value'=>request()->value])->links()}}
            </div>
        </div><!-- /.table-responsive -->
    </form>
</div><!-- /span -->
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="/admin/layer/layer.js"></script>
<script type="text/javascript" src="/admin/js/bootbox.min.js"></script>
<script type="text/javascript" src="/js/common/functions.js"></script>
<script>
    //全选多选框
    $('table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function(){
                this.checked = that.checked;
                $(this).closest('tr').toggleClass('selected');
            });
    });

    //提交分配
    $('.distribution').click(function(){
        // if($('.order_id').checked){
        //     alert(111);
        // }
        $('#form').submit();
    });

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

    //删除订单
    $('.delete-order').click(function () {
        //询问框
        layer.confirm('您是如何看待前端开发？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $('#delform').submit();
        });
    });
</script>
@endsection