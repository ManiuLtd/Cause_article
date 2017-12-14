@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>日期 / 类型</th><th>注册</th><th>开通</th><th>开通率(%)</th><th>开通金额(元)</th><th>退款</th><th>退款金额(元)</th>
            </tr>
            </thead>

            <tbody>
                <tr>
                    <td>今日</td>
                    <td>{{ $today['user_register'] }}</td>
                    <td>{{ $today['membership'] }}</td>
                    <td>{{ $today['membership_rate'] }}</td>
                    <td>{{ number_format($today['order_money'], 2) }}</td>
                    <td>{{ $today['refund'] }}</td>
                    <td>{{ number_format($today['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>同比</td>
                    <td>{{ $today_yesterday['user_register'] }}</td>
                    <td>{{ $today_yesterday['membership'] }}</td>
                    <td>{{ $today_yesterday['membership_rate'] }}</td>
                    <td>{{ number_format($today_yesterday['order_money'], 2) }}</td>
                    <td>{{ $today_yesterday['refund'] }}</td>
                    <td>{{ number_format($today_yesterday['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>昨日</td>
                    <td>{{ $yesterday['user_register'] }}</td>
                    <td>{{ $yesterday['membership'] }}</td>
                    <td>{{ $yesterday['membership_rate'] }}</td>
                    <td>{{ number_format($yesterday['order_money'], 2) }}</td>
                    <td>{{ $yesterday['refund'] }}</td>
                    <td>{{ number_format($yesterday['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>前日</td>
                    <td>{{ $before_yesterday['user_register'] }}</td>
                    <td>{{ $before_yesterday['membership'] }}</td>
                    <td>{{ $before_yesterday['membership_rate'] }}</td>
                    <td>{{ number_format($before_yesterday['order_money'], 2) }}</td>
                    <td>{{ $before_yesterday['refund'] }}</td>
                    <td>{{ number_format($before_yesterday['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>本月</td>
                    <td>{{ $this_month['user_register'] }}</td>
                    <td>{{ $this_month['membership'] }}</td>
                    <td>{{ $this_month['membership_rate'] }}</td>
                    <td>{{ number_format($this_month['order_money'], 2) }}</td>
                    <td>{{ $this_month['refund'] }}</td>
                    <td>{{ number_format($this_month['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>上月</td>
                    <td>{{ $last_month['user_register'] }}</td>
                    <td>{{ $last_month['membership'] }}</td>
                    <td>{{ $last_month['membership_rate'] }}</td>
                    <td>{{ number_format($last_month['order_money'], 2) }}</td>
                    <td>{{ $last_month['refund'] }}</td>
                    <td>{{ number_format($last_month['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>前月</td>
                    <td>{{ $before_last_month['user_register'] }}</td>
                    <td>{{ $before_last_month['membership'] }}</td>
                    <td>{{ $before_last_month['membership_rate'] }}</td>
                    <td>{{ number_format($before_last_month['order_money'], 2) }}</td>
                    <td>{{ $before_last_month['refund'] }}</td>
                    <td>{{ number_format($before_last_month['refund_money'], 2) }}</td>
                </tr>
                <tr>
                    <td>总计</td>
                    <td>{{ $total['user_register'] }}</td>
                    <td>{{ $total['membership'] }}</td>
                    <td>{{ $total['membership_rate'] }}</td>
                    <td>{{ number_format($total['order_money'], 2) }}</td>
                    <td>{{ $total['refund'] }}</td>
                    <td>{{ number_format($total['refund_money'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection