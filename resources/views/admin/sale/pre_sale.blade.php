@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="{{route('sale.pre_index')}}" method="get">
        <select class="form-control" name="key" style="width: 140px">
            <option value="wc_nickname" @if(request()->key == 'wc_nickname') selected @endif>昵称</option>
            <option value="phone" @if(request()->key == 'phone') selected @endif>手机号</option>
        </select>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
    </form>
    <form action="{{ route('sale.pre_distribution') }}" id="form" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="type" value="1">
        <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>用户id</th>
                    <th>微信昵称</th>
                    <th>手机号</th>
                    <th>品牌</th>
                    <th>从业地区</th>
                    <th>推广人/id</th>
                    <th>会员到期时间</th>
                    <th>推广方式</th>
                    <th>推广时间</th>
                    <th>服务标记</th>
                    <th>服务时间</th>
                    <th>创建时间</th>
                </tr>
                </thead>

                <tbody>
                @foreach($sales as $value)
                <tr>
                    <td>{{ $value->user->id }}</td>
                    <td>{{ $value->user->wc_nickname }}</td>
                    <td>{{ $value->user->phone }}</td>
                    <td>@if($value->user->brand) {{ $value->user->brand->name }} @elseif($value->user->brand_id === 0) 全品牌 @endif</td>
                    <td>{{$value->user->employed_area}}</td>
                    <td>{{ $value->user->extension['wc_nickname'].' / '.$value->user->extension['id'] }} </td>
                    <td>{{ $value->user->membership_time }}</td>
                    <td>@if($value->user->ex_type == 1) 文章 @elseif($value->user->ex_type == 2) 二维码 @elseif($value->user->ex_type == 3) 购买页面 @endif</td>
                    <td>{{ $value->user->extension_at }}</td>
                    <td>
                        <a class="btn btn-xs btn-info remark" data="{{ $value->remark }}" data-url="{{ route('sale.service', $value->id) }}">
                            @if($value->remark)
                                {{ subtext($value->remark, 5) }}
                            @else
                                <i class="icon-edit bigger-120"></i>
                            @endif
                        </a>
                    </td>
                    <td>{{ $value->service_at }}</td>
                    <td>{{ $value->user->created_at }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div style="text-align: center">
                {{$sales->appends(['type'=>request()->type,'key'=>request()->key,'value'=>request()->value])->links()}}
            </div>
        </div><!-- /.table-responsive -->
    </form>
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