@extends('admin.layout')
@section('content')
<!--内容-->
<div class="col-xs-12">
    <div class="page-header">
        <h1>日志列表</h1>
    </div>
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>管理员名称</th>
                <th>栏目列表</th>
                <th>栏目链接</th>
                <th>创建时间</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->username}}</td>
                <td>{{$value->action}}</td>
                <td>{{$value->url}}</td>
                <td>{{date('Y-m-d H:i:s',$value->add_time)}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center">
            {{ $list->links() }}
        </div>
    </div>
</div>
<!--内容END-->
@endsection