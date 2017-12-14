@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1> {{v('headtitle')}} </h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="{{ route('admin.report') }}" method="get">
        <select class="form-control" name="type" style="width: 100px">
            <option value="0" @if(request()->type == '0') selected @endif>举报类型</option>
            <option value="内容格式错误" @if(request()->type == '内容格式错误') selected @endif>内容格式错误</option>
            <option value="含色情内容" @if(request()->type == '含色情内容') selected @endif>含色情内容</option>
            <option value="含政治敏感信息" @if(request()->type == '含政治敏感信息') selected @endif>含政治敏感信息</option>
            <option value="包含谣言信息" @if(request()->type == '包含谣言信息') selected @endif>包含谣言信息</option>
            <option value="含暴力敏感信息" @if(request()->type == '含暴力敏感信息') selected @endif>含暴力敏感信息</option>
            <option value="侵权" @if(request()->type == '侵权') selected @endif>侵权</option>
            <option value="其他" @if(request()->type == '其他') selected @endif>其他</option>
        </select>
        <select class="form-control" name="key" style="width: 140px">
            <option value="article" @if(request()->key == 'article') selected @endif>举报文章</option>
            <option value="user" @if(request()->key == 'user') selected @endif>举报用户</option>
        </select>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
    </form>
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>举报文章</th>
                <th>举报类型</th>
                <th>举报用户</th>
                <th>举报内容</th>
                <th>举报时间</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->article['title']}}</td>
                <td>{{$value->type}}</td>
                <td>{{$value->user['wc_nickname']}}</td>
                <td>{{$value->message}}</td>
                <td>{{$value->created_at}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div style="text-align: center">
            {{$list->appends(['type' => request()->type, 'key' => request()->key, 'value' => request()->value])->links()}}
        </div>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection