<script src="/index/js/mescroll.min.js"></script>
<script>
    var mescroll = new MeScroll("{{ $mescroll_id }}", { //第一个参数"mescroll"对应上面布局结构div的id
        //解析: down.callback默认调用mescroll.resetUpScroll(),而resetUpScroll会将page.num=1,再触发up.callback
        down: {
            use: false,
            auto: false,
            isLock: true
        },
        up: {
            callback: upCallback , //上拉加载的回调
            empty: {
                tip: @if($tip) "{{ $tip }}" @else "暂无数据~" @endif, //提示
            },
            page:{num:0,size:{{ $lists->count() }}},
            clearEmptyId: "{{ $html }}", //相当于同时设置了clearId和empty.warpId; 简化写法;默认null
            toTop:{ //配置回到顶部按钮
                src : "/index/image/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
            }
        }
    });

    //上拉加载的回调 page = {num:1, size:10}; num:当前页 默认从1开始, size:每页数据条数,默认10
    function upCallback(page) {
        var url = "{{ $route }}"+"?page="+page.num;

        $.ajax({
            url: url, //如何修改page.num从0开始 ?
            success: function(curPageData) {
                //方法一(推荐): 后台接口有返回列表的总页数 totalPage
                //必传参数(当前页的数据个数, 总页数)
                mescroll.endByPage({{ $lists->count() }}, {{ $lists->lastPage() }});
                $(".{{ $html }}").append(curPageData.html);
                @if($lazyload)
                $(".lazy").lazyload({
                    event: "scrollstop",
                    effect : "fadeIn",
                    container: $(".{{ $html }}"),
                    load:function ($e) {
                        $e.css({"width":"100%","height":"100%"});
                    }
                });
                @endif
            },
            error: function(e) {
                //联网失败的回调,隐藏下拉刷新和上拉加载的状态
                mescroll.endErr();
            }
        });
    }
</script>