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
    <form class="form-horizontal" id="form" role="form" action="{{route('brand.update',['id'=>$res->id])}}">
        {{csrf_field()}}
        {{method_field('PUT')}}

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 品牌名称 </label>
            <div class="col-sm-9">
                <input type="text" name="name" class="col-xs-10 col-sm-5" value="{{$res->name}}" data-rule="*" data-errmsg="品牌名称不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 品牌拼音 </label>
            <div class="col-sm-9">
                <input type="text" name="domain" class="col-xs-10 col-sm-5" value="{{$res->domain}}" data-rule="*" data-errmsg="品牌拼音不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 公众号二维码 </label>
            <div class="col-sm-9">
                <div class="upload-list">
                    <div class="old">
                        <input type="hidden" name="qrcode" value="{{$res->qrcode}}" />
                        <img src="@if($res->qrcode == '') /default.jpg @else /uploads/{{$res->qrcode}} @endif" width="100px" />
                        <span class="deleteImage">修改</span>
                    </div>
                </div>
                <div class="upload-plus" data-field="qrcode" data-upurl="{{route('upload',['file_name'=>'brand_qrcode'])}}" data-token="{{csrf_token()}}" data-multiple="false">
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
