<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    // 	显示
    //	$("#data").click(function(){
    //	    $(".alert").css({"display":"block"});
    //        $(".alert").find(".content").addClass('trans');
    //	});
    $(".alert").css({"display":"block"});
    $(".alert").find(".content").addClass('trans');

    //  品牌
    @include('index.public._brand_list')

    //	关闭
    $(".cuo").click(function(){
        $(".alert").hide();
    })

//    $('.submit').click(function () {
//        $('form').submit();
//    });


</script>