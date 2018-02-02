<script src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.min.js"></script>
<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript">
    // 	显示
    //	$("#data").click(function(){
    //	    $(".alert").css({"display":"block"});
    //        $(".alert").find(".content").addClass('trans');
    //	});

    @if(!session('phone'))
        $(".alert").css({"display":"block"});
        $(".alert").find(".content").addClass('trans');
    @endif

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
                listTpl.push('<div data-id="' + brands[k].id + '">' + brands[k].name + '</div>');
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
    });

    //	关闭
    $(".cuo").click(function(){
        $(".alert").hide();
    })

//    $('.submit').click(function () {
//        $('form').submit();
//    });


</script>