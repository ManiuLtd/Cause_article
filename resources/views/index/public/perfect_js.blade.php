<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    var mySwiper = new Swiper ('.swiper-container', {
        loop: true,
        autoplay:1500,
        pagination: '.swiper-pagination',
        autoplayDisableOnInteraction:false
    });
    // 	显示
    //	$("#data").click(function(){
    //	    $(".alert").css({"display":"block"});
    //        $(".alert").find(".content").addClass('trans');
    //	});
        @if(session()->get('phone') == ''){
        $(".alert").css({"display":"block"});
        $(".alert").find(".content").addClass('trans');
        //  品牌
        $.get("{{route('brand_list')}}",function (ret) {
            console.log(ret.brand_list);
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
                    $(".cenk").val($(this).text());
                    $(".brand_id").val($(this).attr('data-id'));
                    $("#brand").removeClass('show');
                });
                $('#brand .bls').click(function () {
                    $("#brand").removeClass('show');
                })
            });
        })
    }
    @endif
    //	关闭
    $(".cuo").click(function(){
        $(".alert").css({"display":"none"});
    });

//    $('.submit').click(function () {
//        $('form').submit();
//    });

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
                    showMsg('完善资料成功', 1, 2000);
                    if (ret.url) {
                        setTimeout(function () {
                            window.location.href = ret.url;
                        }, 2000);
                    }else{
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    showMsg('完善资料失败');
                }
            },'json');
        }
    });
</script>