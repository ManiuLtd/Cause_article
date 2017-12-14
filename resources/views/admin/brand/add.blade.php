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
    <form class="form-horizontal" id="form" role="form" action="{{route('brand.store')}}">
        {{csrf_field()}}

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 品牌名称 </label>
            <div class="col-sm-9">
                <input type="text" name="name" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="品牌名称不可为空"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">不可为空</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-1 control-label no-padding-right"> 品牌拼音 </label>
            <div class="col-sm-9">
                <input type="text" name="domain" class="col-xs-10 col-sm-5" data-rule="*" data-errmsg="品牌拼音不可为空"/>
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
                        <input type="hidden" name="qrcode" />
                        <img src="/default.jpg" width="100px" />
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
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'detail' ,{ //这里的 mycontent就是上面我们设置的textarea或者input元素的id
        filebrowserBrowseUrl:"",//设置上传图片的页面为ckfinder.html
        filebrowserImageBrowseUrl:'',
        filebrowserFlashBrowseUrl: '',
        filebrowserUploadUrl: '',
        filebrowserImageUploadUrl: "{:url('index/detailimg')}",
        filebrowserFlashUploadUrl: "",
        width:1060,//设置默认宽度为900px
        height:400  //设置默认高度是300px，这个高度是不包含顶部菜单的高度
    });

//    new checkForm({
//        form : '#formm',
//        btn : '#submitt',
//        error : function (e,msg){
//            showMsg(msg);
//        },
//        complete : function (form){
//            var url = form.getAttribute('action'),
//                datas = $(form).serializeArray();
//            $.post(url,datas,function (ret) {
//                swal({
//                    title: "", text: ret.msg, timer: 2000, type: !!ret.code ? 'success' : 'error', showConfirmButton: false
//                });
//                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
//            });
//        }
//    });
</script>
@endsection
