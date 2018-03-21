@extends('admin.layout')
@section('content')
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    <form class="form-inline" style="margin-bottom: 15px" action="{{route('photo.index')}}" method="get">
        <select class="form-control" name="brand_id" style="width: 140px">
            <option value="0" @if(request()->brand_id == 0) selected @endif>全品牌</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @if(request()->brand_id == $brand->id) selected @endif>{{ $brand->name }}</option>
            @endforeach
        </select>
        <select class="form-control" name="type" style="width: 140px">
            <option value="0" @if(request()->brand_id == 0) selected @endif>类型</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}" @if(request()->type == $type->id) selected @endif>{{ $type->name }}</option>
            @endforeach
        </select>
        <span style="font-size: 16px">名称：</span>
        <input type="text" name="value" class="input" value="{{request()->value}}">
        <button class="btn btn-sm btn-info" type="submit">&nbsp;搜索&nbsp;</button>
    </form>

    <button class="btn btn-success store" data-url="{{route('photo.create')}}" style="margin-bottom: 15px">新增普通美图</button>

    <button class="btn btn-success store" data-url="{{route('photo_brand.create')}}" style="margin-bottom: 15px">新增品牌美图</button>

    <div class="table-responsive">
        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>美图</th>
                <th>名称</th>
                <th>所属类型</th>
                <th>所属品牌</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
            </thead>

            <tbody>
            @foreach($list as $value)
            <tr>
                <td><img src="{{ $value->url }}" width="110px"></td>
                <td>{{ $value->name }}</td>
                <td>@if($value->type) {{ $value->type->name }} @endif</td>
                <td>@if($value->brand) {{ $value->brand->name }} @elseif($value->brand_id === 0) 全品牌 @endif</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $value->updated_at }}</td>
                <td>
                    <div class="visible-md visible-lg hidden-sm hidden-xs btn-group">

                        @if($value->type)
                            <button class="btn btn-xs btn-info store" data-url="{{route('photo.edit', $value->id)}}" style="width:26px;height: 26px;">
                                <i class="icon-edit bigger-120"></i>
                            </button>
                        @elseif($value->brand || $value->brand_id === 0)
                            <button class="btn btn-xs btn-info store" data-url="{{route('photo_brand.edit', $value->id)}}" style="width:26px;height: 26px;">
                                <i class="icon-edit bigger-120"></i>
                            </button>
                        @endif

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
        <div style="text-align: center">
            {{$list->appends(['type'=>request()->type,'name'=>request()->name,'brand_id'=>request()->brand_id])->links()}}
        </div>
    </div><!-- /.table-responsive -->
</div><!-- /span -->
@endsection