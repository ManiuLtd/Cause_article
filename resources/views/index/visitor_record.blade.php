<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>访客记录</title>
	@include('index.public.css')
	<style>
		.mescroll-totop{bottom: 70px !important;}
		@if($list->isEmpty())
        	#lists{position: absolute;left: 50%;top: 50%;transform: translate(-50%,-50%);}
		@endif
	</style>

</head>
<body>
<div id="visitor" class="flexv wrap">
	<div class="flex flipbox">
		<i class="flex center bls bls-horn"></i>
		<div class="flexitem center bor">
			<div class="flex flip">
				<div class="flex center text"> 开通VIP后，可查看访客记录，精准锁定潜在客户</div>
			</div>
		</div>
	</div>
	<div class="flexitemv mainbox box mescroll" id="mescroll">
		<div class="flexv centerv around front">
			<a href="javascript:;" class="flexitemv center myfront">
				<em class="flex">{{ $today_see }}</em>
				<div class="flex">
					<span class="flex center">今日浏览</span>
				</div>
			</a>
			<div class="flex line"></div>
			<a href="{{ route('visitor_prospect') }}" class="flexitemv center myfront">
				<em class="flex">{{ count($prospect) }}</em>
				<div class="flex">
					<span class="flex center">准客户</span>
				</div>
			</a>
		</div>

		<div class="lists" id="lists">

		</div>
	</div>

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_information')

	@include('index.public.footer')

</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="https://at.alicdn.com/t/font_568864_xyn4a976gw7mn29.js"></script>
<script src="/index/js/mescroll.min.js"></script>
@includeWhen(!$user->brand_id && !$user->phone, 'index.public.perfect_js')
<script>
    $(".cuo").hide();
    //滚动提示
    var num = 0;
    setInterval(function () {
        if (num <= -($(".text").width())) {
            num = $(".text").width();
        }
        num -= 1;
        $(".flip").css({
            left: num
        })
    },35);

	@includeWhen(!$user->brand_id && !$user->phone, 'index.public._infomation_js')

    var mescroll = new MeScroll("mescroll", { //第一个参数"mescroll"对应上面布局结构div的id
            //解析: down.callback默认调用mescroll.resetUpScroll(),而resetUpScroll会将page.num=1,再触发up.callback
            down: {
                use: false,
                auto: false,
                isLock: true
            },
            up: {
                callback: upCallback , //上拉加载的回调
                empty: {
                    // icon: "/images/mescroll-totop.png", //图标,默认null
                    tip: "快把文章分享给好友吧~", //提示
                },
                page:{num:0,size:5},
                clearEmptyId: "lists", //相当于同时设置了clearId和empty.warpId; 简化写法;默认null
                toTop:{ //配置回到顶部按钮
                    src : "/index/image/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
                }
            }
        });

    //上拉加载的回调 page = {num:1, size:10}; num:当前页 默认从1开始, size:每页数据条数,默认10
    function upCallback(page) {
        var url = "{{ route('visitor_record') }}"+"?page="+page.num;

        $.ajax({
            url: url, //如何修改page.num从0开始 ?
            success: function(curPageData) {
                //方法一(推荐): 后台接口有返回列表的总页数 totalPage
                //必传参数(当前页的数据个数, 总页数)
                mescroll.endByPage({{ $list->count() }}, {{ $list->lastPage() }});
                $("#lists").append(curPageData.html);
            },
            error: function(e) {
                //联网失败的回调,隐藏下拉刷新和上拉加载的状态
                mescroll.endErr();
            }
        });
    }
</script>
</html>