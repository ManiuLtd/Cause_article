<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="format-detection" content="telephone=no">
	<title>阅读分享</title>
	@include('index.public.css')
</head>
<body>
<div id="read" class="flexv">
	<div class="flexitemv mainbox box">
		<div class="flex center title">
			<a href="{{ route('read_share', 1) }}" class="flex center read @if(request()->type == 1) elect @endif">阅读</a>
			<a href="{{ route('read_share', 2) }}" class="flex center share @if(request()->type == 2) elect @endif">分享</a>
		</div>
		
		<div class="shares">

		</div>
	</div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>
<script src="/index/js/mescroll.min.js"></script>
<script type="text/javascript">
    // $(".read").click(function(){
    //     $(".title span").removeClass('elect');
	 //    $(this).addClass('elect');
    //     initMescroll('read')
	 //    $('.reads').css({'display':'block'});
		// $('.shares').css({'display':'none'});
    // });
    // $(".share").click(function(){
    //     $(".title span").removeClass('elect');
    //     $(this).addClass('elect');
    //
    //     $('.shares').css({'display':'block'});
    //     $('.reads').css({'display':'none'});
    // });

    //下拉分页
    var mescroll = new MeScroll("body", { //第一个参数"mescroll"对应上面布局结构div的id
        //解析: down.callback默认调用mescroll.resetUpScroll(),而resetUpScroll会将page.num=1,再触发up.callback
        down: {
            use: false,
            auto: false,
            isLock: true
        },
        up: {
            page: {num:0,size:5},
            callback: upCallback , //上拉加载的回调
            toTop:{ //配置回到顶部按钮
                src : "/index/image/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
                offset : 1000
            }
        }
    });

    //上拉加载的回调 page = {num:1, size:10}; num:当前页 默认从1开始, size:每页数据条数,默认10
    function upCallback(page) {
        var url = "{{ route('read_share', request()->type) }}"+"?page="+page.num;
        $.ajax({
            url: url, //如何修改page.num从0开始 ?
            success: function(curPageData) {
                //方法一(推荐): 后台接口有返回列表的总页数 totalPage
                //必传参数(当前页的数据个数, 总页数)
                mescroll.endByPage({{ $lists->count() }}, {{ $lists->lastPage() }});
                $(".shares").append(curPageData.html);
            },
            error: function(e) {
                //联网失败的回调,隐藏下拉刷新和上拉加载的状态
                mescroll.endErr();
            }
        });
    }
</script>
</html>