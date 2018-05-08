/**插入信息填写及品牌
 * @param id [插入位置id]
 * @param btn [触发按钮]
 * @param val [品牌名称填充]
 * @param brandInput [品牌 ID 填充]
 * @param selectedBrand [用户已选品牌]
 */
function InfoBrand(id,btn,val,brandInput,brands, selectedBrand) {
    $(id).append(`<div class="company_fixed_list">
        <div class="top-bar">
            <a href="javascript:;" class="prev-btn"></span></a>
            <h3>选择公司</h3>
        </div>
        <div class="company_new_fixed">
            <div class="company_new_area">
                <div class="line_x"></div>
                <ul class="company_lists commend_list">
                    
                </ul>
                <div class="title_line_x">A-F</div>
                <ul class="company_lists company_af">
                    <!--<li class="comp" data-brand="0">全品牌</li>-->
                </ul>
                <div class="title_line_x">G-L</div>
                <ul class="company_lists company_gl"></ul>
                <div class="title_line_x">M-R</div>
                <ul class="company_lists company_mr"></ul>
                <div class="title_line_x">S-Z</div>
                <ul class="company_lists company_sz" style="padding-bottom: 30px"></ul>
                <!--<div class="writer_input writer_input_com">找不到我的公司，点这里&gt;&gt;</div>-->
            </div>
        </div>
    </div>`);

    var A_F = [], G_L = [], M_R = [],S_Z = [];
    for (var k = 0; k < brands.length; k++) {
        var ch = brands[k].pinyin.substring(0, 1);
        if ('a'<= ch && ch <= 'f') {
            A_F.push('<li class="comp" data-brand="' + brands[k].id + '">' + brands[k].title + '</li>');
        } else if('g'<= ch && ch <= 'l'){
            G_L.push('<li class="comp" data-brand="' + brands[k].id + '">' + brands[k].title + '</li>');
        } else if('m'<= ch && ch <= 'r'){
            M_R.push('<li class="comp" data-brand="' + brands[k].id + '">' + brands[k].title + '</li>');
        } else if('s'<= ch && ch <= 'z'){
            S_Z.push('<li class="comp" data-brand="' + brands[k].id + '">' + brands[k].title + '</li>');
        }
    }
    $('.company_af').append(A_F.join(''));
    $('.company_gl').append(G_L.join(''));
    $('.company_mr').append(M_R.join(''));
    $('.company_sz').append(S_Z.join(''));
    if (selectedBrand) {
        $('.commend_list').append(`<li class="comp" data-brand="${selectedBrand[0]}">${selectedBrand[1]}</li>`);
    }

    // 选择品牌--记录点击过的品牌
    var HOT = [], hot = [];
    if (selectedBrand) {
        HOT = [{id: selectedBrand[0], title: selectedBrand[1]}];
    }
    $(btn).click(function () {
       $(id).show();
        $(".company_lists>li").click(function () {
            $('.selected').remove();  //删除自定义选项
            hot.splice(0,hot.length);
            var $this = $(this);
            var selected = {
                id: $this.data('brand'),
                title: $this.text()
            };
            $(val).val(selected.title);
            $(brandInput).val(selected.id);
            var hasBrand = HOT.some(function (item) {
              return item.id == selected.id;

            });
            // if(key == 0) return;
            if(! hasBrand){
                HOT.unshift(selected);
            }else{
                HOT = HOT.filter(function (item) {
                  return item.id != selected.id;
                });
                HOT.unshift(selected);
            }
            if(HOT.length > 6) HOT.splice(6,1);
            for(var i=0; i< HOT.length; i++){
                hot.push('<li class="comp" data-brand="' + HOT[i].id + '">' + HOT[i].title + '</li>');
            }
            $('.commend_list').children('.comp').remove();
            $('.commend_list').append(hot.join(''));
            $(id).hide();
        });
    });

    //关闭品牌窗
    $(".prev-btn").click(function () {
        $(id).hide();
    });

    // 弹出自定义窗
    $(".writer_input_com").click(function () {
        $(id).append(`<div class="dialog-wrap" style="display: block;">
            <div class="dialog-mask"></div>
            <form class="dialog-form">
                <div class="dialog-hd">手动输入</div>
                <div class="dialog-bd">
                    <div class="input-item"><input type="text" name="company" data-rule="cname" data-errmsg="品牌名称只能为中文" maxlength="10" placeholder="请输入公司名字"></div>
                </div>
                <div class="dialog-ft clearfix">
                    <button type="button" class="phone-submit-btn">确定</button>
                </div>
                <a href="javascript:;" class="dialog-close-btn"></a>
            </form>
        </div>`);

        $("input[name=company]").focus();
        // 验证表单
        new checkForm({
            form : '.dialog-form',
            btn : '.phone-submit-btn',
            error : function (ele,err){showMsg(err);},
            complete : function (ele){
                var $customBrandName = $(ele).serializeArray()[0].value;
                $(val).val($customBrandName);
                $(brandInput).val($customBrandName);
                $(".commend_list").prepend('<li class="selected">'+$customBrandName+'</li>');
                $('.commend_list li:last').remove();
                $(".dialog-wrap").remove();
                $(id).hide();
            }
        });
        // 关闭自定义窗
        $(".dialog-close-btn").click(function () {
            $(".dialog-wrap").remove();
        });
    });
}
