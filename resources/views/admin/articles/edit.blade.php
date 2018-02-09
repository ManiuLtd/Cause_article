@extends('admin.layout')
@section('content')
<link rel="stylesheet" href="/css/admin/upload.css">
<!-- 百度编辑器引用 -->
<script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.config.js') }}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.all.min.js') }}"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
<!-- 百度编辑器引用 -->
<style>
    label, .lbl{
        vertical-align: baseline;
    }
</style>
<div class="col-xs-12">
    <div class="page-header">
        <h1>{{v('headtitle')}}</h1>
    </div>
    <form class="form-horizontal" id="form" role="form" action="{{route('articles.update',['id'=>$res->id])}}">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <input type="hidden" name="id" value="{{ $res->id }}">

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 文章类型 </label>
            <div class="col-sm-2">
                <select name="type" class="form-control" id="form-field-select-1">
                    <option value="0" @if($res->type == 0) selected @endif>全类型</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" @if($res->type == $type->id) selected @endif>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 所属品牌 </label>
            <div class="col-sm-2">
                <select name="brand_id" class="form-control" id="form-field-select-1">
                    <option value="0" @if($res->brand_id == 0) selected @endif>全品牌</option>
                    @foreach($brand as $value)
                        <option value="{{$value->id}}" @if($value->id == $res->brand_id) selected @endif>{{$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 标题 </label>
            <div class="col-sm-9">
                <input type="text" name="title" class="col-xs-10 col-sm-5" value="{{$res->title}}" data-rule="*" data-errmsg="文章标题不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 封面 </label>
            <div class="col-sm-9">
                <div class="upload-list">
                    <div class="old">
                        <input type="hidden" name="pic" value="{{$res->pic}}"/>
                        <img src="@if($res->pic == '') /default.jpg @else {{$res->pic}} @endif" width="100px"/>
                        <span class="deleteImage">修改</span>
                    </div>
                </div>
                <div class="upload-plus" data-field="pic" data-upurl="{{route('upload',['file_name'=>'articles'])}}"  data-multiple="false">
                    <i class="icon-cloud-upload"></i>
                </div>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 详细内容 </label>
            <div class="col-sm-9">
                {{--<textarea name="details" id="detail" style="width: 450px;height: 100px;">{{$res->details}}</textarea>--}}
                <script id="editor" type="text/plain" style="width:1024px;height:500px;">{!! $res->details !!}</script>
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
{{--<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>--}}
<script type="text/javascript">
    {{--CKEDITOR.replace( 'detail' ,{ //这里的 mycontent就是上面我们设置的textarea或者input元素的id--}}
        {{--filebrowserBrowseUrl:"",//设置上传图片的页面为ckfinder.html--}}
        {{--filebrowserImageBrowseUrl:'',--}}
        {{--filebrowserFlashBrowseUrl: '',--}}
        {{--filebrowserUploadUrl: '',--}}
        {{--filebrowserImageUploadUrl: "{{ route('ckeditor_image') }}",--}}
        {{--filebrowserFlashUploadUrl: "",--}}
        {{--width:1060,//设置默认宽度为900px--}}
        {{--height:400,  //设置默认高度是300px，这个高度是不包含顶部菜单的高度--}}
        {{--pasteFilter:null,--}}
        {{--allowedContent:true,--}}
    {{--});--}}
        //实例化编辑器
        var ue = UE.getEditor('editor');

</script>
@endsection
