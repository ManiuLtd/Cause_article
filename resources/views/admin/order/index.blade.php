@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
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
                <th>订单金额</th>
                <th>会员类型</th>
                <th>是否支付</th>
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
                <td>{{ $value->price }}</td>
                <td>@if($value->type == 1)一个月@else一年@endif</td>
                <td>@if($value->state == 1)已支付@elseif($value->state == 2)支付失败@else未支付@endif</td>
                <td>{{ $value->created_at }}</td>
                <td>
                    @if($value->state == 1 && $value->refund_state != 1)
                    <a class="btn btn-xs btn-info wx-refund" data-url="{{ route('admin.refund', $value->id) }}" data-price="{{ $value->price }}">退款</a>
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
<script type="text/javascript" src="/js/common/functions.js"></script>
<script>
    $('.wx-refund').click(function () {
        var url = $(this).attr('data-url'),
            max_price = $(this).attr('data-price');
        var content = '退款金额<input class="bootbox-input form-control money" type="text">';
        bootbox.dialog({
            title: '填写退款金额：',
            message: content,
            buttons: {
                "success" : {
                    "label" : "确认退款",
                    "className" : "btn-success",
                    "callback": function() {
                        var money = $('.money').val();
                        if(parseInt(money) > parseInt(max_price)) {
                            showMsg('退款金额不可大于支付金额')
                        }
                        if(money == '' || money == 0){
                            showMsg('退款金额不可为空或0')
                        }
                        $.post(url, { money:money, _token:'{{csrf_token()}}' }, function (ret) {
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
</script>
@endsection