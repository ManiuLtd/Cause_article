@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    @if(has_menu($menu,'/admin_group/create'))
    <button class="btn btn-success store" data-url="{{route('admin_group.create')}}" style="margin-bottom: 15px">新增用户组</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>名称</th>
                <th>状态</th>
                <th>创建时间</th>
                <th>更新时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->title}}</td>
                <td>@if($value->state == 0) 关闭 @else 开放 @endif</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'admin_group/edit'))
                        <button class="btn btn-xs btn-info store" data-url="{{route('admin_group.edit', ['id'=>$value['id']])}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>
                        @endif
                        @if(has_menu($menu,'admin_group/delete'))
                        <form action="{{route('admin_group.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" data-msg="将删除此用户组" style="width: 26px">
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