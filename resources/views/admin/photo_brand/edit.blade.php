@extends('admin.layout')
@section('content')
    <link rel="stylesheet" href="/css/admin/upload.css">
<style>
    label, .lbl{
        vertical-align: baseline;
    }
</style>
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    <form class="form-horizontal" id="form" role="form" action="{{route('photo.update',$res->id)}}">
        {{csrf_field()}}
        {{method_field('PUT')}}

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 所属品牌 </label>
            <div class="col-sm-2">
                <select name="brand_id" class="form-control" id="form-field-select-1">
                    <option value="0" @if($res->brand_id == 0) selected @endif>全品牌</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @if($brand->id == $res->brand_id) selected @endif>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 名称 </label>
            <div class="col-sm-9">
                <input type="text" name="name" value="{{ $res->name }}" class="col-xs-10 col-sm-5"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 美图 </label>
            <div class="col-sm-9">
                <div class="upload-list">
                    <div class="old">
                        <input type="hidden" name="url" value="{{$res->url}}" />
                        <img src="{{$res->url}}" width="100px" />
                        <span class="deleteImage">修改</span>
                    </div>
                </div>
                <div class="upload-plus" data-field="url" data-upurl="{{route('upload',['file_name'=>'extension_photo'])}}" data-token="{{csrf_token()}}" data-multiple="false">
                    <i class="icon-cloud-upload"></i>
                </div>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="col-md-offset-1 col-md-9">
            <button class="btn btn-info" type="button" id="submit">
                <i class="icon-ok bigger-110"></i>
                添加
            </button>

            <button class="btn" type="reset">
                <i class="icon-undo bigger-110"></i>
                清空
            </button>
        </div>
    </form>
</div>
@endsection
