@extends('admin.layout')
@section('content')
<link rel="stylesheet" href="/css/admin/upload.css">

<div class="main-content" style="margin-left: 100px">
    <div class="main-content-inner">
        <div class="row">
            <div class="col-xs-12">
                <form class="form-horizontal" id="form" action="{{route('admin_user.store')}}" method="post">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right"> 所属分组 </label>
                        <div class="col-sm-2">
                            <select name="gid" class="form-control" id="form-field-select-1">
                                @foreach($select as $value)
                                <option value="{{$value->id}}">{{$value->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right"> 头像 </label>
                        <div class="col-sm-9">
                            <div class="upload-list">
                                <div class="old">
                                    <input type="hidden" name="head" />
                                    <img src="http://image.wmpian.cn/admin/default.jpg" />
                                    <span class="deleteImage">修改</span>
                                </div>
                            </div>
                            <div class="upload-plus" data-field="head" data-upurl="{{route('upload',['file_name'=>'admin_users'])}}" data-multiple="false">
                                <i class="icon-cloud-upload"></i>
                            </div>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right"> 管理员名 </label>
                        <div class="col-sm-9">
                            <input type="text" name="account" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="管理员名不能为空"/>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle">不可为空(作为账号登录)</span>
                            </span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right"> 密码 </label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="密码不能为空"/>
                            <span class="help-inline col-xs-12 col-sm-7">
                                <span class="middle">不可为空</span>
                            </span>
                        </div>
                    </div>
                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label no-padding-right"> 状态 </label>

                        <div class="col-sm-9">
                            <label>
                                <input name="state" checked="checked" class="ace ace-switch ace-switch-5" type="checkbox" value="1">
                                <span class="lbl"></span>
                                <span class="help-inline col-xs-12 col-sm-7" style="width: 100px">
                                    <span class="lbl"> 启用或禁用 </span>
                                </span>
                            </label>
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
        </div>
    </div>
</div>
@endsection