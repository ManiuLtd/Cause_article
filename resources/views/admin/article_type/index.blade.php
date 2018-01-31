@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    @if(has_menu($menu,'/admin/articles/create'))
    <button class="btn btn-success store" data-url="{{route('article_type.create')}}" style="margin-bottom: 15px">新增文章类型</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>文章类型名称</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                        <button class="btn btn-xs btn-info store" data-url="{{route('article_type.edit',$value->id)}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>

                        <form action="{{route('article_type.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" style="width: 26px" data-msg="将删除此文章类型">
                                <i class="icon-trash bigger-120"></i>
                            </a>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection