@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    @if(has_menu($menu,'/admin/banner/create'))
    <button class="btn btn-success store" data-url="{{route('banner.create')}}" style="margin-bottom: 15px">新增banner图</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>banner图</th>
                <th>跳转链接</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td><img src="/uploads/{{$value->image}}" width="200px" height="100px"></td>
                <td>{{$value->url}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'banner/edit'))
                        <button class="btn btn-xs btn-info store" data-url="{{route('banner.edit',['id'=>$value->id])}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>
                        @endif
                        @if(has_menu($menu,'banner/delete'))
                        <form action="{{route('banner.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" style="width: 26px" data-msg="将删除此banner图">
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