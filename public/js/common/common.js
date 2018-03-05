/**
 * Created by Administrator on 2017/4/15 0015.
 */
$('.store').click(function () {
    var url = $(this).attr('data-url');
    window.location.href = url;
});

$('.change').click(function () {
    var url = $(this).attr('data-url');
    layer.confirm('提交后不可修改，您确定要提交吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        $.get(url,function (ret) {
            console.log(ret);
            if(ret.code==1) {
                layer.msg(ret.msg, {icon: 1});
                setTimeout(function () {
                    if (ret.url) window.location.href = ret.url;
                }, 1000)
            }else{
                layer.msg(ret.msg, {icon: 2});
            }
        })
    });
});

$('.operation').click(function () {
    var url = $(this).attr('data-url');
    $.get(url,function (ret) {
        swal({
            title: "", text: ret.msg, timer: 2000, type: ret.code==1 ? 'success' : 'error', showConfirmButton: false
        });
        if(ret.url){
            setTimeout(function () {
                window.location.href = ret.url;
            },1000)
        }
    })
});

$('.common').click(function () {
    var msg = $(this).attr('data-msg'),
        p = $(this).parent();
    layer.confirm(msg+'，您确定吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        p.submit();
        // $.get(url,function (ret) {
        //     console.log(ret);
        //     layer.msg(ret.msg, {icon: 1});
        //     setTimeout(function () {
        //         if(ret.url) window.location.href = ret.url;
        //         else window.location.reload();
        //     },1000)
        // })
    });
});

new checkForm({
    form : '#form',
    btn : '#submit',
    error : function (e,msg){
        showMsg(msg,0,1000,'#form');
    },
    complete : function (form){
        // console.log(1111111111111);
        // $('textarea').val();.replace(/<img\b[^>]*?\bsrc[\s]*=[\s]*["']?[\s]*(<imgUrl>[^"'>]*)[^>]*?[\s]*>/, function (match, capture) {
        //     console.log(capture);
        // });return;
        var url = form.getAttribute('action');
        var datas = $(form).serializeArray();
        // if ( um.getContent() == '') {
        //     // var content = CKEDITOR.instances.detail.getData();
        //     var content = um.getContent();
        //     var obj = {
        //         name:  'details',
        //         value: content
        //     };
        //     datas.push(obj);
        // }
        console.log(datas);
        $.post(url,datas,function(ret){
            if(ret.state == 0) {
                showMsg(ret.msg, 1);
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            }else{
                showMsg(ret.msg);
            }
        },'json');
    }
});