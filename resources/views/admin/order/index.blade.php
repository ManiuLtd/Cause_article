@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle') ?? '订单总列表'}}  </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="" method="get">
        <select class="form-control" name="state" style="width: 140px">
            <option value="1" @if(request()->state == '1') selected @endif>已支付</option>
        </select>
        <select class="form-control" name="key" style="width: 140px">
            <option value="wc_nickname" @if(request()->key == 'wc_nickname') selected @endif>昵称</option>
            <option value="phone" @if(request()->key == 'phone') selected @endif>手机号</option>
        </select>
        <input type="text" name="value" class="input" value="{{ request()->value }}">
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
                <th>操作</th>
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
                    @else<color style="color: red;font-weight: bold;">未支付@endif</color>
                </td>
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
                <td>
                    @if($value->state == 1 && $value->refund_state != 1)
                        <a class="btn btn-xs btn-info wx-refund" data-url="{{ route('admin.refund', $value->id) }}" data-price="{{ $value->price }}" data-name="{{ $value->user->wc_nickname }}">退款</a>
                    @endif
                    @if($value->state != 1)
                        <form action="{{ route('admin.order_del', $value->id) }}" id="delform" method="post">
                            {{ csrf_field() }}
                            <a class="btn btn-xs btn-danger delete-order">删除订单</a>
                        </form>
{{--                        <a class="btn btn-xs btn-info" data-url="{{ route('admin.refund', $value->id) }}">删除订单</a>--}}
                    @endif
                </td>
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
    $('.wx-refund').click(function () {
        var url = $(this).attr('data-url'),
            max_price = $(this).attr('data-price'),
            nickname = $(this).attr('data-name');
        var content = '退款金额<input class="bootbox-input form-control money" type="text">';
        bootbox.dialog({
            title: '填写退款金额(退款人：'+nickname+')：',
            message: content,
            buttons: {
                "success" : {
                    "label" : "确认退款",
                    "className" : "btn-success",
                    "callback": function() {
                        showProgress('正在退款中...');
                        var money = $('.money').val();
                        if(parseInt(money) > parseInt(max_price)) {
                            showMsg('退款金额不可大于支付金额');
                            return;
                        }
                        if(money == 0){
                            showMsg('退款金额不可为0');
                            return;
                        }
                        $.post(url, { money:money, _token:'{{csrf_token()}}' }, function (ret) {
                            hideProgress();
                            if(ret.state == 200) {
                                showMsg(ret.message, 1, 2000);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showMsg(ret.message, 0, 2000);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            }
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