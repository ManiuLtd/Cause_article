@extends('admin.layout')
@section('content')
<style>
    .widget-header > .widget-title{
        line-height: 36px;
        padding: 0;
        margin: 0;
        display: inline;
    }
</style>
<div class="main-content">
    <div class="main-content-inner">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" id="form" action="{{route('admin_group.store')}}" method="post">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 用户组名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="title" id="title" placeholder="用户组名" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="用户组名称不能为空">
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle">用户组名称，不能为空。</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-2"> 是否启用 </label>
                        <div class="control-label no-padding-left col-sm-1">
                            <label>
                                <input name="state" id="status" checked="checked" class="ace ace-switch ace-switch-2" type="checkbox" value="1" />
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> 权限选择 </label>
                        <div class="col-sm-9">
                            <div class="col-sm-10">
                                @foreach($rule as $value)
                                <div class="row">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <label>
                                                    <input name="rule[]" class="ace ace-checkbox-2 father" type="checkbox" value="{{$value['id']}}"/>
                                                    <span class="lbl" title="{{$value['title']}}"> {{subtext($value['title'],7)}}</span>
                                                </label>
                                            </h4>
                                            <div class="widget-toolbar">
                                                @if(!empty($value['children']))
                                                <a href="#" data-action="collapse">
                                                    <i class="arrow icon-angle-down"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!empty($value['children']))
                                        <div class="widget-body">
                                            <div class="widget-main row">
                                                @foreach($value['children'] as $child)
                                                <label class="col-xs-2" style="width:160px;">
                                                    <input name="rule[]" class="ace ace-checkbox-2 children" type="checkbox" value="{{$child['id']}}"/>
                                                    <span class="lbl" title="{{$child['title']}}"> {{subtext($child['title'],7)}}</span>
                                                </label>
                                                @if(!empty($child['children']))
                                                @foreach($child['children'] as $c)
                                                <label class="col-xs-2" style="width:160px;">
                                                    <input name="rule[]" class="ace ace-checkbox-2 children" type="checkbox" value="{{$c['id']}}"/>
                                                    <span class="lbl" title="{{$c['title']}}"> {{subtext($c['title'],7)}} </span>
                                                </label>
                                                @endforeach
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-offset-2 col-md-9">
                        <button class="btn btn-info submit" type="button" id="submit">
                            <i class="icon-ok bigger-110"></i>
                            提交
                        </button>

                        <button class="btn" type="reset">
                            <i class="icon-undo bigger-110"></i>
                            重置
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script>
    $(".children").click(function(){
        $(this).parent().parent().parent().parent().find(".father").prop("checked", true);
    });

    $(".father").click(function(){
        if(this.checked){
            $(this).parent().parent().parent().parent().find(".children").prop("checked", true);
        }else{
            $(this).parent().parent().parent().parent().find(".children").prop("checked", false);
        }
    });
</script>
@endsection
