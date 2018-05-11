@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{ $admin->account . request()->pay_at }}  </h1>
    </div>
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
                    <td>备注</td>
                    <th>创建时间</th>
                </tr>
            </thead>

            <tbody>
                @foreach($orders as $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->user->wc_nickname }}</td>
                        <td>{{ $value->user->phone }}</td>
                        <td>{{ optional($value->user->brand)->name }}</td>
                        <td>{{ $value->user->membership_time }}</td>
                        <td>{{ number_format($value->price, 2) }}</td>
                        <td>@if($value->type == 1) 一个月 @elseif($value->type == 2) 一年 @elseif($value->type == 3) 两年 @endif</td>
                        <td>
                            @if($value->state == 1)
                                <color style="color: green;font-weight: bold;">已支付</color>
                            @elseif($value->state == 2)
                                <color style="color: red;font-weight: bold;">支付失败</color>
                            @else<color style="color: red;font-weight: bold;">未支付@endif</color>
                        </td>
                        <td>
                            {{ subtext($value->remark, 5) }}
                        </td>
                        <td>{{ $value->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection