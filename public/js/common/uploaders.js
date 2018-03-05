// function upload(num) {
$('body').on('click','.deleteImage',function (){
	if($(this).hasClass('cannot')) return;
	if($(this).attr('data-url')){
		var url = $(this).attr('data-url'),obj = $(this);
		showAlert('','您确定删除该图片？删除后不可恢复！',[{'t':'确定','u':url},{'t':'取消'}],function (key,url){
			if(key == 0){
				showProgress('删除中..');
				$.getJSON(url,function (ret){
					showMsg(ret.info,ret.status);
					if(ret.status == 1){
						obj.parent().remove();
						$('.upload-plus').addClass('show');
					}
				});
			}
		});
	}else{
		$(this).parent().remove();
		$('.upload-plus').addClass('show');
	}
});
var picker = '.upload-plus',upObject = $(picker),field = upObject.attr('data-field');
var serverUrl = upObject.attr('data-upurl'),multiple = eval(upObject.attr('data-multiple')),token = upObject.attr('data-token');
var boxw = upObject.width(),boxh = upObject.height();
var uploader = WebUploader.create({
	auto : true,
	swf : 'http://cdn.bootcss.com/webuploader/0.1.1/Uploader.swf',
	server : serverUrl,
	accept : {
		title: 'Images',
		extensions: 'jpg,jpeg,bmp,png',
		mimeTypes: 'image/jpg,image/jpeg,image/png'
	},
    formData: {
        _token: token
    },
    compress: false,
	pick : {
		id : picker,
		multiple : multiple
	}
});
uploader.on('uploadProgress',function (err){
	console.log(err);
});

uploader.on('fileQueued',function (file){
	var _input = multiple ? '<input type="hidden" name="' + field + '[]" />' : '<input type="hidden" name="' + field + '" />';
	var _template = $('<div class="old" id="' + file.id + '"><img /><span class="deleteImage cannot">预览中...</span>' + _input + '</div>');
	_template.appendTo($('.upload-list'));
	var _img = _template.find('img');
	uploader.makeThumb(file,function(error,src){
		if(error){
			_template.find('.deleteImage').text('预览失败');
		}else{
			_template.find('.deleteImage').text('上传中...');
			_img.attr('src',src);
		}
	},boxw,boxh);
	if(multiple == false) $('.upload-plus').removeClass('show');
});

uploader.on('uploadProgress',function (file,percentage){
	$('#' + file.id).find('.deleteImage').text('上传进度' + parseInt(percentage) + '%');
});

uploader.on('uploadSuccess',function (file,ret){
	var _text = multiple ? '删除' : '修改';
	$('#' + file.id).find('.deleteImage').removeClass('cannot').text(_text);
	$('#' + file.id).find('input[type=hidden]').val(ret.saveName);
});
uploader.on('uploadError',function (file){
	$('#' + file.id).find('.deleteImage').removeClass('cannot').text('失败,可删除');
});

/************************111111111111*************************/

$('.deleteImage1').click(function () {
    if($(this).hasClass('cannot')) return;
    if($(this).attr('data-url')){
        var url = $(this).attr('data-url'),obj = $(this);
        showAlert('','您确定删除该图片？删除后不可恢复！',[{'t':'确定','u':url},{'t':'取消'}],function (key,url){
            if(key == 0){
                showProgress('删除中..');
                $.getJSON(url,function (ret){
                    showMsg(ret.info,ret.status);
                    if(ret.status == 1){
                        obj.parent().remove();
                        $('.upload-plus1').addClass('show');
                    }
                });
            }
        });
    }else{
        $(this).parent().remove();
        $('.upload-plus1').addClass('show');
    }
});
var picker1 = '.upload-plus1',upObject1 = $(picker1),field1 = upObject1.attr('data-field');
var serverUrl1 = upObject1.attr('data-upurl'),multiple1 = eval(upObject1.attr('data-multiple'));
var boxw1 = upObject1.width(),boxh1 = upObject1.height();
var uploader1 = WebUploader.create({
    auto : true,
    swf : 'http://cdn.bootcss.com/webuploader/0.1.1/Uploader.swf',
    server : serverUrl1,
    accept : {
        title: 'Images',
        extensions: 'jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    },
    compress: {
        width: 1000,
        height: 1000
    },
    fileSingleSizeLimit: 1*1024*1024,
    pick : {
        id : picker1,
        multiple : multiple1
    }
});
uploader1.on('uploadProgress',function (err){
    console.log(err);
});

uploader1.on('fileQueued',function (file){
    var _input = multiple1 ? '<input type="hidden" name="' + field1 + '[]" />' : '<input type="hidden" name="' + field1 + '" />';
    var _template = $('<div class="old" id="' + file.id + '"><img /><span class="deleteImage1 cannot">预览中...</span>' + _input + '</div>');
    _template.appendTo($('.upload-list1'));
    var _img = _template.find('img');
    uploader1.makeThumb(file,function(error,src){
        if(error){
            _template.find('.deleteImage1').text('预览失败');
        }else{
            _template.find('.deleteImage1').text('上传中...');
            _img.attr('src',src);
        }
    },boxw1,boxh1);
    if(multiple1 == false) $('.upload-plus1').removeClass('show');
});

uploader1.on('uploadProgress',function (file,percentage){
    $('#' + file.id).find('.deleteImage1').text('上传进度' + parseInt(percentage) + '%');
});

uploader1.on('uploadSuccess',function (file,ret){
    var _text = multiple1 ? '删除' : '修改';
    $('#' + file.id).find('.deleteImage1').removeClass('cannot').text(_text);
    $('#' + file.id).find('input[type=hidden]').val(ret.saveName);
});
uploader1.on('uploadError',function (file){
    $('#' + file.id).find('.deleteImage1').removeClass('cannot').text('失败,可删除');
});






















