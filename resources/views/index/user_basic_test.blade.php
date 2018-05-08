<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>基本信息</title>
	<link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
	<link rel="stylesheet" href="/index/css/icon.css">
	<link rel="stylesheet" href="/index/css/LArea.css">
	<link rel="stylesheet" href="/index/css/index.css">
	<link rel="stylesheet" href="/index/css/reset.css">
</head>
<body>
<div id="basic" class="flexv wrap">
	<form action="{{route('test',$res->id)}}" id="form" enctype="multipart/form-data" method="post">
		{{csrf_field()}}
		<div class="flexitemv user">
			<div class="users">
				<div class="item portrait">
					<span class="flex centerv">头像</span>
					<div class="flex centerv right">
						<div class="flex center userimg">
							<img src="{{ $res->head }}" class="fitimg">
						</div>
						<i class="flex center bls bls-yjt"></i>
					</div>
					<input type="file" id="userimg" class="fileElem" accept="image/jpg,image/png,image/jpeg">
					<input type="hidden" name="head" value="{{ $res->head }}">
				</div>
				<div class="item name">
					<span class="flex centerv">姓名</span>
					<div class="flex centerv right">
						<input type="text" name="wc_nickname" class="flexi center userimg" value="{{ $res->wc_nickname }}" maxlength="11" data-rule="*" data-errmsg="请填写您的姓名">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
				<div class="item phone">
					<span class="flex centerv">手机号</span>
					<div class="flex centerv right">
						<input type="tel" name="phone" class="flex center userimg" value="{{ $res->phone }}" data-rule="m" data-errmsg="手机号码格式错误">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
			</div>
			<div class="users">
				<div class="item brand">
					<span class="flex centerv">我的品牌</span>
					<div class="flex centerv right">
						<input readonly="readonly" class="flex center userimg brand_name" type="text" placeholder="请选择" @if($res->brand_id == 0) value="全品牌" @else value="{{$res->brand['name']}}" @endif data-rule="*" data-errmsg="请选择您的品牌" unselectable="on" onfocus="this.blur()">
						<input type="hidden" name="brand_id" class="brand_id" value="{{ $res->brand_id }}">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
				<div class="item area">
					<span class="flex centerv">职业</span>
					<div class="flex centerv right">
						<input id="profession" name="profession" class="flex center userimg" type="text" value="{{$res->profession}}" data-rule="profession" data-errmsg="职业最多6个字">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
				<div class="item area">
					<span class="flex centerv">从业地区</span>
					<div class="flex centerv right">
						<input id="sel_city" name="employed_area" readonly="readonly" class="flex center userimg" type="text" placeholder="请选择" value="{{$res->employed_area}}" data-rule="*" data-errmsg="请填写您的从业地区" unselectable="on" onfocus="this.blur()">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
			</div>
			<div class="users">
				<div class="item qrcode">
					<span class="flex centerv">个人微信二维码</span>
					<div class="flex centerv right">
						@if($res->qrcode)
							<div class="flex center userimg">
								<img src="{{ $res->qrcode }}" class="fitimg">
							</div>
							<i class="flex center bls bls-yjt"></i>
						@else
							<img src="/index/image/upload_qrcode.jpg" class="flex center userimg">
							<i class="flex center bls bls-yjt"></i>
						@endif
					</div>
				<input type="file" id="qrcodeimg" class="fileElem" accept="image/jpg,image/png,image/jpeg">
				<input type="hidden" name="qrcode" value="{{ $res->qrcode }}">
				</div>
			</div>
			<a href="{{route('qecode_help')}}" class='clook'>查看如何获取二维码？</a>
			<div class="button">
				<a href="javascript:;" class="flex center" id="submit">保存</a>
			</div>
		</div>
	</form>

	<!--品牌-->
	<div id="brand" class="flexitemv" data-id="{{ $res->brand ? $res->brand_id : '' }}" data-name="{{ $res->brand ? $res->brand->name : '' }}"></div>
	
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/hammer.js/2.0.4/hammer.min.js"></script>
<script src="https://cdn.bootcss.com/iScroll/5.1.3/iscroll-zoom.min.js"></script>
<script src="/index/js/jquery.cliper.js"></script>
<script src="/index/js/city.js"></script>
<script src="/index/js/picker.min.js"></script>
<script src="/index/js/city-js.js"></script>
<script src="/index/js/hammer.min.js"></script>
<script src="/index/js/avatarUpload.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript" src="/index/js/brand_new.js"></script>
<script type="text/javascript">
   var brands = {!! $brands !!};
   var brandContainer = $('#brand');
   var selectedBrand = '{{ $res->brand }}' ? [brandContainer.data('id'), brandContainer.data('name')] : null;
   // 插入品牌
   InfoBrand('#brand','.brand_name','.brand_name', '.brand_id', brands, selectedBrand);

    function showCliper (id,cid,title,callback){
        var template = '<div id="' + id.replace('#','') + '" class="cliper">' +
            '<div class="header">' +
            '<a href="javascript:;" class="side cancel">取消</a>' +
            '<div class="title">' + title + '</div>' +
            '<a href="javascript:;" class="side confirm">确定</a>' +
            '</div>' +
            '<div class="cliperbox" id="' + cid.replace('#','') + '"></div>' +
            '</div>';
        $(template).appendTo($('body'));
        if(typeof callback == 'function') callback(id,cid,id +' .confirm');
        $(id).find('.cancel').click(function (){
            $(id).css('opacity',0);
            setTimeout(function (){$(id).css('z-index',-1);},500);
        });
    }

    //   头像上传
    $(".portrait").click(function () {
        showCliper('#headclip','#headbox','裁剪头像',function (id,cid,ok){
            var hammer = '',currentIndex = 0,name = '',box = 200,scale = 0.5;
            $(cid).cliper({
                width : box,
                height : box,
                file: '#userimg',
                ok : ok,
                strictSize : scale,
                pickError : function (){
                    showMsg('图片格式错误！');
                },
                loadStart: function (file) {
                    showProgress('照片读取中');
                    name = file.name

                    ;
                },
                loadError : function (err){
                    showMsg('图片读取失败');
                },
                loadComplete: function () {
                    hideProgress();
                    $(id).css({'z-index':99,'opacity':1});
                },
                clipFinish: function (data) {
                    $(".portrait img").attr("src",data);
                    $("input[name=head]").val(data);
                    $("#headclip").remove();
                }
            });
        });
    })


    //  上传二维码
    $(".qrcode").click(function(){
        showCliper('#headclip', '#headbox', '裁剪头像', function (id, cid, ok) {
            var hammer = '', currentIndex = 0, name = '', box = 200, scale = 0.5;
            $(cid).cliper({
                width: box,
                height: box,
                file: '#qrcodeimg',
                ok: ok,
                strictSize: scale,
                pickError: function () {
                    showMsg('图片格式错误！');
                },
                loadStart: function (file) {
                    showProgress('照片读取中');
                    name = file.name

                    ;
                },
                loadError: function (err) {
                    showMsg('图片读取失败');
                },
                loadComplete: function () {
                    hideProgress();
                    $(id).css({'z-index': 99, 'opacity': 1});
                },
                clipFinish: function (data) {
                    $(".qrcode img").attr("src", data);
                    $("input[name=qrcode]").val(data);
                    $("#headclip").remove();
                }
            });
        });
    })

    new checkForm({
        form : '#form',
        btn : '#submit',
        error : function (ele,err){showMsg(err);},
        complete : function (ele){
            var url = $(ele).attr('action'),post = new FormData(ele);
            showProgress('正在修改');
            $.ajax({
				 url: url,
				 type: 'POST',
				 data: post,
				 contentType: false, // 注意这里应设为false
				 processData: false,
				 success: function(ret) {
				     hideProgress();
					if(ret.code == 0){
						showMsg(ret.errormsg,1);
						if(ret.url) setTimeout(function () {
							window.location.href = ret.url;
                        },1000);
					}else{
					    showMsg(ret.errormsg);
					}
				 },
				 error: function (jqXHR) {
					 console.log(JSON.stringify(jqXHR));
				 }
		 	})
        }
    });
</script>
</html>