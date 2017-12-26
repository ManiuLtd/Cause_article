@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    @if(has_menu($menu,'/admin_user/create'))
    <button class="btn btn-success store" data-url="{{route('admin_user.create')}}" style="margin-bottom: 15px">新增用户</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>姓名</th>
                <th>用户组</th>
                <th>状态</th>
                <th>创建时间</th>
                <th>最新登陆时间</th>
                <th>ip地址</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->account}}</td>
                <td>{{$value->group['title']}}</td>
                <td>@if($value->state == 1) 启用 @else 禁用 @endif</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->login_time}}</td>
                <td>{{$value->login_ip}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'admin_user/edit'))
                        <button class="btn btn-xs btn-info store" data-url="{{route('admin_user.edit',['id'=>$value->id])}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>
                        @endif
                        @if(has_menu($menu,'admin_user/delete'))
                        <form action="{{route('admin_user.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" data-msg="删除管理员" style="width: 26px">
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