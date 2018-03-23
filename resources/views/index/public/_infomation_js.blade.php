new checkForm({
    form : '#form',
    btn : '#submit',
    error : function (ele,err){showMsg(err);},
    complete : function (ele){
        var url = $(ele).attr('action'),post = $(ele).serializeArray();
        showProgress('正在提交');
        console.log(post);
        $.post(url,post,function (ret){
            hideProgress();
            if(ret.state == 0) {
            showMsg('完善资料成功', 1, 1500);
            setTimeout(function () {
                window.location.reload();
            }, 1500);
        } else {
            showMsg('完善资料失败');
        }
    },'json');
    }
});