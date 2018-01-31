@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>

    <button class="btn btn-success store" data-url="{{route('photo.create')}}" style="margin-bottom: 15px">新增美图</button>

    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>美图</th>
                <th>名称</th>
                <th>所属类型</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td><img src="/uploads/{{ $value->url }}" width="110px"></td>
                <td>{{ $value->name }}</td>
                <td>{{$value->type->name}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                        <button class="btn btn-xs btn-info store" data-url="{{route('photo.edit', $value->id)}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>

                        <form action="{{route('photo.destroy', $value->id)}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" style="width: 26px" data-msg="将删除此美图">
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