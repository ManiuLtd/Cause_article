@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    @if(has_menu($menu,'/admin/articles/create'))
    <button class="btn btn-success store" data-url="{{route('brand.create')}}" style="margin-bottom: 15px">新增品牌</button>
    @endif
    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>品牌名称</th>
                <th>公众号二维码</th>
                <th>拼音</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td>{{$value->name}}</td>
                <td><img src="/uploads/{{$value->qrcode}}" alt="{{$value->name}}" width="100px"></td>
                <td>{{$value->domain}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                        @if(has_menu($menu,'articles/edit'))
                        <button class="btn btn-xs btn-info store" data-url="{{route('brand.edit',['id'=>$value->id])}}" style="width:26px;height: 26px;">
                            <i class="icon-edit bigger-120"></i>
                        </button>
                        @endif
                        @if(has_menu($menu,'articles/delete'))
                        <form action="{{route('articles.destroy',['id'=>$value['id']])}}" method="post">
                            {{csrf_field()}}
                            {{ method_field('DELETE') }}
                            <a class="btn btn-xs btn-danger common" style="width: 26px" data-msg="将删除此品牌">
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