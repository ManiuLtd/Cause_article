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
    <form class="form-horizontal" id="form" role="form" action="{{route('menu.store')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 上级栏目 </label>
            <div class="col-sm-2">
                <select name="pid" class="form-control" id="form-field-select-1">
                    <option value="0">顶级栏目</option>
                    @foreach($select as $value)
                        <option value="{{$value['id']}}">{{$value['title']}}</option>
                        @if(!empty($value['children']))
                            @foreach($value['children'] as $child)
                                <option value="{{$child['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;┗━{{$child['title']}}</option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 标题 </label>
            <div class="col-sm-9">
                <input type="text" name="title" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="栏目标题不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 图标 </label>
            <div class="col-sm-9">
                <input type="text" name="icon" class="col-xs-10 col-sm-5"/>
                <span class="help-inline col-xs-12 col-sm-7">
                <span class="middle">ace模板图标</span>
            </span>
            </div>
        </div>

        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 链接地址 </label>
            <div class="col-sm-9">
                <input type="text" name="url" class="col-xs-10 col-sm-5" placeholder="格式如：Index/index"/>
            </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 排序 </label>
            <div class="col-sm-9">
                <input type="number" name="sort" class="col-xs-10 col-sm-5" />
                <span class="help-inline col-xs-12 col-sm-7">
                <span class="lbl"> 越小越前</span>
            </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 是否显示 </label>

            <div class="col-sm-9">
                <label>
                    <input name="display" checked="checked" class="ace ace-switch ace-switch-5" type="checkbox" value="1">
                    <span class="lbl"></span>
                </label>
            </span>
            </div>
        </div>

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