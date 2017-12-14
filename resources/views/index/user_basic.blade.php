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
	<form action="{{route('user_basic')}}" id="form" enctype="multipart/form-data" method="post">
		{{csrf_field()}}
		<div class="flexitemv user">
			<div class="users">
				<div class="item portrait">
					<span class="flex centerv">头像</span>
					<div class="flex centerv right">
						<div class="flex center userimg">
							<img src="{{$res->head}}" class="fitimg">
						</div>
						<i class="flex center bls bls-yjt"></i>
					</div>
					<input type="file" class="fileElem" accept="image/jpg,image/png,image/jpeg">
				</div>
				<div class="item name">
					<span class="flex centerv">姓名</span>
					<div class="flex centerv right">
						<input type="text" name="wc_nickname" class="flexi center userimg" value="{{$res->wc_nickname}}" data-rule="*" data-errmsg="请填写您的姓名">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
				<div class="item phone">
					<span class="flex centerv">手机号</span>
					<div class="flex centerv right">
						<input type="tel" name="phone" class="flex center userimg" value="{{$res->phone}}" data-rule="m" data-errmsg="手机号码格式错误">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
			</div>
			<input type="hidden" name="head">
			<div class="users">
				<div class="item brand">
					<span class="flex centerv">我的品牌</span>
					<div class="flex centerv right">
						<input readonly="readonly" class="flex center userimg" type="text" placeholder="请选择" @if($res->brand != null) value="{{$res->brand['name']}} @endif" data-rule="*" data-errmsg="请选择您的品牌">
						<input type="hidden" name="brand_id" class="brand_id" value="{{$res->brand_id}}">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
				<div class="item area">
					<span class="flex centerv">从业地区</span>
					<div class="flex centerv right">
						<input id="sel_city" name="employed_area" readonly="readonly" class="flex center userimg" type="text" placeholder="请选择" value="{{$res->employed_area}}" data-rule="*" data-errmsg="请填写您的从业地区">
						<i class="flex center bls bls-yjt"></i>
					</div>
				</div>
			</div>
			<div class="users">
				<div class="item qrcode">
					<span class="flex centerv">个人微信二维码</span>
					<div class="flex centerv right">
						<div class="flex center userimg">
							@if($res->qrcode)
								<img src="{{$res->qrcode}}" class="fitimg">
							@else
								请上传
							@endif
						</div>
						<i class="flex center bls bls-yjt"></i>
					</div>
					<input type="file" class="fileElem" accept="image/jpg,image/png,image/jpeg">
				</div>
			</div>
			<input type="hidden" name="qrcode">
			<a href="{{route('qecode_help')}}" class='clook'>查看如何获取二维码？</a>
			<div class="button">
				<a href="javascript:;" class="flex center" id="submit">保存</a>
			</div>
		</div>
	</form>

	<!-- 二维码上传 -->
	<div id="conWrap" class='mainbox'>
		<!--拖动选择层-->
		<div id="picture">
			<div id="bg"></div>
			<div id="mask"></div>
		</div>
		<!--操作按钮-->
		<div id="button">
			<div id="select" class="active">取消</div>
			<div id="preview">确定</div>
		</div>
		<!--用于生成和预览-->
		<div id="canvasWrap">
			<canvas id="canvas"></canvas>
		</div>
	</div>
	<!-- 二维码上传END -->
	
	{{--<div class="flex center bottom">&copy;&ensp;2017&ensp;事业头条&ensp;版权所有</div>--}}
	<!--品牌-->
	<div id="brand" class="flexv dialog_box">
		<div class="flex center head">
			<a href="javascript:;" class="bls bls-zjt"></a>
			<h1 class="flexitem center" style="margin-left: -2rem;">选择品牌</h1>
		</div>
		<ul class="flexitemv mainbox company" style="padding-top: 20px">
		
		</ul>
		<ul class="lettrt">
			<li><a href="#">#</a></li>
			<li><a href="#A">A</a></li>
			<li><a href="#B">B</a></li>
			<li><a href="#C">C</a></li>
			<li><a href="#D">D</a></li>
			<li><a href="#E">E</a></li>
			<li><a href="#F">F</a></li>
			<li><a href="#G">G</a></li>
			<li><a href="#H">H</a></li>
			<li><a href="#I">I</a></li>
			<li><a href="#J">J</a></li>
			<li><a href="#K">K</a></li>
			<li><a href="#L">L</a></li>
			<li><a href="#M">M</a></li>
			<li><a href="#N">N</a></li>
			<li><a href="#O">O</a></li>
			<li><a href="#P">P</a></li>
			<li><a href="#Q">Q</a></li>
			<li><a href="#R">R</a></li>
			<li><a href="#S">S</a></li>
			<li><a href="#T">T</a></li>
			<li><a href="#U">U</a></li>
			<li><a href="#V">V</a></li>
			<li><a href="#W">W</a></li>
			<li><a href="#X">X</a></li>
			<li><a href="#Y">Y</a></li>
			<li><a href="#Z">Z</a></li>
		</ul>
	</div>
	
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/index/js/city.js"></script>
<script src="/index/js/picker.min.js"></script>
<script src="/index/js/city-js.js"></script>
{{--<script src="/index/js/brand.js"></script>--}}
<script src="/index/js/hammer.min.js"></script>
<script src="/index/js/avatarUpload.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    $.get("{{route('brand_list')}}",function (ret) {
        //  品牌
        var brands = ret.brand_list;
        var char = '', charlist = [];
        var charTpl = [], listTpl = [];
        for (var k = 0; k < brands.length; k++) {
            var ch = brands[k].domain.substring(0, 1);
            if (char == ch) {
                charlist[char].push(brands[k]);
                listTpl.push('<div>' + brands[k].name + '</div>');
            } else {
                if (char != '') listTpl.push('</li>');
                char = ch;
                charlist[char] = [brands[k]];
                listTpl.push('<li id="' + char.toUpperCase() + '">');
                listTpl.push('<p>' + char.toUpperCase() + '</p>');
                listTpl.push('<div data-id="' + brands[k].id + '">' + brands[k].name + '</div>');
                charTpl.push('<li><a href="#' + char + '">' + char.toUpperCase() + '</a></li>');
            }
        }
        listTpl.push('</li>');
        $(".company").append(listTpl.join(''));
//   选择
        $(".brand").click(function () {
            $("#brand").addClass('show');
            $("#brand ul li div").click(function () {
                $(".brand").find('.userimg').val($(this).text());
                $(".brand_id").val($(this).attr('data-id'));
                $("#brand").removeClass('show');
            });
            $('#brand .bls').click(function () {
                $("#brand").removeClass('show');
            })
        });
    });
	//   头像上传
    $(".portrait").click(function () {
        $("#bg").attr('style',"");
        $("#conWrap").css("transform","translateY(0)");
        Elem($(this).find("input"));
    });

//  上传二维码
    $(".qrcode").click(function () {
        $("#bg").attr('style',"");
        $("#conWrap").css("transform","translateY(0)");
        Elem($(this).find("input"));
    });

    function Elem(file) {
        var options = {
            containerId: "#picture",
            uploadBgId: "#bg",
            fileId: file,
            canvasId: "#canvas",
            //容器尺寸
            container: {
                width: $("#picture").width(),
                height: $("#picture").height()
            },
            //裁剪区域尺寸
            clip: {
                width: $("#mask").width(),
                height: $("#mask").height()
            },
            //图片质量0-1
            imgQuality: 1
        };
        //获取操作对象
        var txUpload = avatarUpload(options);

        //文件 onchange事件
        file.on("change", function () {
            txUpload.handleFiles(function () {
                //当用户选择文件后 按钮active
                $("#preview").addClass('active');
            })
        });
        //选择文件
        $("#select").click(function () {
            $("#conWrap").css("transform", "translateY(100%)");
        });
        //预览
        $("#preview").click(txUpload.createImg);
    }

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