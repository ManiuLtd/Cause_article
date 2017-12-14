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
    <form class="form-horizontal" action="{{route('web_config')}}" method="post">
        {{csrf_field()}}

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 网站提示 </label>
            <div class="col-sm-9">
                <textarea name="web_tip" cols="30" rows="6" style="width: 300px">{{$res->web_tip}}</textarea>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="col-md-offset-1 col-md-9">
            @if(has_menu($menu,'index/add_config'))
            <button class="btn btn-info" type="submit">
                <i class="icon-ok bigger-110"></i>
                添加
            </button>

            <button class="btn" type="reset">
                <i class="icon-undo bigger-110"></i>
                清空
            </button>
            @endif
        </div>
    </form>
</div>
@endsection