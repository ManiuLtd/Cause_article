<script type="text/javascript" src="/index/js/checkform.js"></script>
<script type="text/javascript" src="/index/js/functions.js"></script>
<script type="text/javascript" src="/index/js/brand_new.js"></script>
<script type="text/javascript">

    $(".alert").css({"display":"block"});
    $(".alert").find(".content").addClass('trans');

    //  品牌
    {{--@include('index.public._brand_list')--}}
    $.get("{{ route('brand_list') }}", function (ret) {
        console.log(ret.brand_list);
        var brands = ret.brand_list;
        var brandContainer = $('#brand');
        var selectedBrand = '{{ $user->brand }}' ? [brandContainer.data('id'), brandContainer.data('name')] : null;
        // 插入品牌
        InfoBrand('#brand','.brand_name','.brand_name', '.brand_id', brands, selectedBrand);
    });

    //	关闭
    $(".cuo").click(function(){
        $(".alert").hide();
    })

</script>