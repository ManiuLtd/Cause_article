@extends('admin.layout')
@section('content')
<link rel="stylesheet" href="/css/admin/upload.css">
<!-- 百度编辑器引用 -->
<link href="{{ asset('umeditor/themes/default/_css/umeditor.css') }}" type="text/css" rel="stylesheet">
<script type="text/javascript" src="{{ asset('umeditor/third-party/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('umeditor/third-party/template.min.js') }}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('umeditor/umeditor.config.js') }}"></script>
<script type="text/javascript" src="{{ asset('umeditor/editor_api.js') }}"></script>
<script type="text/javascript" src="{{ asset('umeditor/lang/zh-cn/zh-cn.js') }}"></script>
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
    <form class="form-horizontal" id="form" role="form" action="{{route('articles.store')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 文章类型 </label>
            <div class="col-sm-2">
                <select name="type" class="form-control" id="form-field-select-1">
                    <option value="4">全类型</option>
                    <option value="1">事业资讯</option>
                    <option value="2">产品资讯</option>
                    <option value="3">直销资讯</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 所属品牌 </label>
            <div class="col-sm-2">
                <select name="brand_id" class="form-control" id="form-field-select-1">
                    <option value="0">全品牌</option>
                    @foreach($brand as $value)
                    <option value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 标题 </label>
            <div class="col-sm-9">
                <input type="text" name="title" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="文章标题不可为空"/>
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
                        <input type="hidden" name="pic" />
                        <img src="http://image.wmpian.cn/admin/default.jpg" />
                        <span class="deleteImage">修改</span>
                    </div>
                </div>
                <div class="upload-plus" data-field="pic" data-upurl="{{route('upload',['file_name'=>'articles'])}}" data-token="{{csrf_token()}}" data-multiple="false">
                    <i class="icon-cloud-upload"></i>
                </div>
            </div>
        </div>
        <div class="space-4"></div>


        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 详细内容 </label>
            <div class="col-sm-9">
                {{--<textarea name="details" id="detail" style="width: 450px;height: 100px;"></textarea>--}}
                <!--style给定宽度可以影响编辑器的最终宽度-->
                <script type="text/plain" id="myEditor" style="width:800px;height:240px;">
                    <p>这里我可以写一些输入提示</p>
                </script>
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
    var um = UM.getEditor('myEditor');
    um.addListener('blur',function(){
        $('#focush2').html('编辑器失去焦点了')
    });
    um.addListener('focus',function(){
        $('#focush2').html('')
    });
</script>
@endsection
