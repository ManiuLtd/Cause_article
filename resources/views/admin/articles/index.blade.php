@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    @if(has_menu($menu,'/admin/articles/create'))
    <button class="btn btn-success store" data-url="{{route('articles.create')}}" style="margin-bottom: 15px">新增文章</button>
    @endif
    <form class="form-inline" style="margin-bottom: 15px" action="{{route('articles.index')}}" method="get">
        <select class="form-control" name="brand" style="width: 100px">
            <option value="0" @if(request()->brand == 0) selected @endif>全品牌</option>
            @foreach($brand_list as $value)
            <option value="{{ $value->id }}" @if(request()->brand == $value->id) selected @endif>{{ $value->name }}</option>
            @endforeach
        </select>
        <select class="form-control" name="type" style="width: 100px">
            @foreach($types as $type)
                <option value="{{ $type->id }}" @if(request()->type == $type->id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
        <select class="form-control" name="key" style="width: 120px">
            <option value="title" @if(request()->key == 'title') selected @endif>标题</option>
        </select>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
        <a href="{{ route('articles.index') }}" class="btn btn-sm btn-info">&nbsp;返回列表&nbsp;</a>
    </form>
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th width="300px">标题</th>
                <th width="100px">封面</th>
                <th>阅读数</th>
                <th>分享数</th>
                <th>文章类型</th>
                <th>所属品牌</th>
                <th width="170px">创建时间</th>
                <th width="170px">修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->title}}</td>
                <td><img src="/uploads/{{$value->pic}}" width="100px"></td>
                <td>{{$value->read}}</td>
                <td>{{$value->share}}</td>
                <td>{{ $value->article_type->name }}</td>
                <td>@if($value->brand){{$value->brand['name']}}@else 全品牌 @endif</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'articles/edit'))
                        <button class="btn btn-xs btn-info store" data-url="{{route('articles.edit',['id'=>$value->id])}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>
                        @endif
                        @if(has_menu($menu,'articles/delete'))
                        <form action="{{route('articles.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" style="width: 26px" data-msg="将删除此文章">
                                <i class="icon-trash bigger-120"></i>
                            </a>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection