@extends('admin.layout')
@section('content')

<style>
    label, .lbl{
        vertical-align: baseline;
    }
</style>
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    <form class="form-horizontal" id="form" role="form" action="{{route('photo_type.update',['id'=>$res->id])}}">
        {{csrf_field()}}
        {{method_field('PUT')}}

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 类型名称 </label>
            <div class="col-sm-9">
                <input type="text" name="name" class="col-xs-10 col-sm-5" value="{{$res->name}}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">越小越前</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 排序 </label>
            <div class="col-sm-9">
                <input type="text" name="sort" class="col-xs-10 col-sm-5" value="{{$res->sort}}" data-rule="*" data-errmsg="排序不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
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
