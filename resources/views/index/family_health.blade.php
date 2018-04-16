<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>在线咨询</title>
    <style type="text/css">
        *{margin: 0;padding: 0;}
        html,body{width:100%;background: #E5E5E5;}
        input::-webkit-input-placeholder{color:#333;}
        .flex{display: -webkit-box;}
        .flexv{display: -webkit-box; -webkit-box-orient: vertical;}  /*垂直排列*/
        .centerv{-webkit-box-align: center;}  /*垂直居中*/
        .centerh{-webkit-box-pack: center;}  /*水平居中*/
        .center{-webkit-box-align: center; -webkit-box-pack: center;}
        .container{min-height:100%;}
        .container .clear {padding-top:8px;}
        .clear:after{display:block;content:"";clear:both;visibility:hidden;height:0;}
        .container dl{float:left;position:relative;clear:both;display:block;padding: 0 40px 0 15px;margin:0 0 15px 0;}
        .container dl dt{display:inline-block;float:left;}
        .container dl dt div.avatar{width:40px;height:40px;border-radius:3px;overflow:hidden;}
        .container dl dt div.avatar img{width:100%;}
        .container dl dd .content{position:relative;}
        .container dl dd{box-shadow: 0 0 3px rgba(0,0,0,.05);border: 1px solid #D5D5D5;margin-left:54px;background:#fff;font-size:14px;padding:8px 10px;border-radius:5px;}
        .container dl dd .content div{line-height:16px;}
        .container dl dd .content div button,.site{display:block;clear:both;text-align:center;border: 1px solid #639838;border-radius:5px;padding: 8px 12px;background:#fff;outline:none;margin: 8px 0 0 0;width:100%;font-size:13px;}
        .container dl dd .content div button.active{color:#ccc;border-color:#fff;}
        .container dl dd div.button{padding-bottom:5px;}
        .container dl dd .content div textarea{border: 1px solid #ccc;border-radius:2px;color:#aaa;padding:10px;outline:none;margin: 6px 0 0 0;resize:none;}
        .container dl dd .content .arrow{position:absolute;left:-15px;top:2px;width:8px;height:8px;background:#fff;transform:rotate(45deg);border-left: 1px solid #d5d5d5;border-bottom: 1px solid #d5d5d5;}
        .container dl.right{float:right;padding: 0 15px 0 40px;}
        .container dl.right dt{position:absolute;right:15px;}
        .container dl.right dd{border: 1px solid #79B746;margin:0 54px 0 0;background:#C0E278;padding-top:4px;}
        .container dl.right dd .content .arrow{border-left:0;border-bottom:0;border-right:1px solid #79B746;border-top:1px solid #79B746;left:auto;right:-15px;background:#C0E278;}
        .hide{display: none;}
        #hint{width:fit-content;;height:3rem;background:rgba(0,0,0,.5);padding: 0 10px;position:fixed;top:50%;left:50%;transform:translateX(-50%);border-radius:10px;text-align:center;line-height:3rem;color:#fff;display:none;}
    </style>
</head>
<body>
<div class="container">
    <!--问题一-->
    <div class="p1 hide clear" name="p1" rel="button" style="display:block;">
        <dl class="clear" style="padding-top:15px;">
            <dt>
                <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">您好，我是{{ $user->wc_nickname }}，请问您想了解什么呢？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
                <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <button rel="咨询健康问题">咨询健康问题</button>
                        <button rel="了解加盟事业">了解加盟事业</button>
                        <button rel="其他">其他</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题二-->
    <div class="p2 hide clear" name="p2" rel="input">
        <dl class="clear">
            <dt>
                <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">您在那个地区工作？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
                <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <input class="site site_get" style="width:auto;" type="text" readonly="readonly" rel="地理位置" title="点击获取地理位置" placeholder="点击获取地理位置" unselectable="on" onfocus="this.blur()">
                        <span style="display:block;width:auto;text-align:center;margin-top:8px;">若需修改，点上面</span>
                        <button>下一步</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题三-->
    <div class="p3 hide clear" name="p3" rel="textarea">
        <dl class="clear">
            <dt>
                <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">怎么称呼您？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
                <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <textarea rel="您的姓名" title="请填写您的姓名">请填写您的姓名</textarea>
                        <button class="js_sex_button" rel="1">先生</button>
                        <button class="js_sex_button" rel="0">女士</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题四-->
    <div class="p4 hide clear" name="p4" rel="textarea">
        <dl class="clear">
            <dt>
                <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">怎么联系到您？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
                <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <textarea rel="您的手机号码" title="请填写您的手机号码" maxlength="11" id="mobile">请填写您的手机号码</textarea>
                        <button id="mobile_submit">确定</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题五-->
    <div class="p5 hide clear" name="p5" rel="button">
        <dl class="clear">
            <dt>
            <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">您的家庭结构是怎样的？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
            <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <button rel="单身贵族">单身贵族</button>
                        <button rel="单身有娃">单身有娃</button>
                        <button rel="已婚无娃">已婚无娃</button>
                        <button rel="已婚有娃">已婚有娃</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题六-->
    <div class="p6 hide clear" name="p6" rel="input">
        <dl class="clear">
            <dt>
            <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">您现在的年龄是？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
            <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <input id="dateShow" class="site" type="text" readonly="readonly" onfocus="this.blur()" rel="您的出生日期" placeholder="请选择出生日期">
                        <button>下一步</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--问题七-->
    <div class="p7 hide clear" name="p7" rel="button">
        <dl class="clear">
            <dt>
            <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">您的年收入大约是在哪个范围？</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
            <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <button rel="0-10万">0-10万</button>
                        <button rel="10-20万">10-20万</button>
                        <button rel="20-40万">20-40万</button>
                        <button rel="40-60万">40-60万</button>
                        <button rel="60-100万">60-100万</button>
                        <button rel=">100万">>100万</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <!--结束-->
    <div class="p8 hide clear" name="p8" rel="end">
        <dl class="clear">
            <dt>
                <div class="avatar"><img src="{{ $user->head }}"/></div>
            </dt>
            <dd>
                <div class="content">
                    <div class="text">收到，<img src="/index/image/smile.png" align="absmiddle" width="24"/>感谢您参与测评，正在计算请稍等...</div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
        <dl class="right clear">
            <dt>
                <div class="avatar"><img src="{{ session('head_pic') }}"/></div>
            </dt>
            <dd>
                <div class="content button">
                    <div class="text">
                        <button id="mobile_end">查看报告</button>
                    </div>
                    <div class="arrow"></div>
                </div>
            </dd>
        </dl>
    </div>
    <form method="post" action="{{ route('family_appraisal_begin') }}" id="f1">
        {{ csrf_field() }}
        <input type="hidden" name="type" id="p1" value=""/>
        <input type="hidden" name="region" id="p2" value=""/>
        <input type="hidden" name="name" id="p3" value=""/>
        <input type="hidden" name="phone" id="p4" value=""/>
        <input type="hidden" name="family" id="p5" value=""/>
        <input type="hidden" name="age" id="p6" value=""/>
        <input type="hidden" name="income" id="p7" value=""/>
        <input type="hidden" name="gender" id="p8" value=""/>
        <input type="hidden" name="user_id" value="{{ request()->user->id }}"/>
    </form>
    <div id="fill" style="clear: both; float: left; visibility: hidden;"></div>
    <!--弹框提示-->
    <div id="hint"></div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script class="js" src="http://pv.sohu.com/cityjson"></script>
<script src="/index/js/iScroll.js"></script>
<script src="/index/js/Mdate.js"></script>
<script type="text/javascript">
    // 地址选择
    var swith = true;
    $(".site_get").click(function(){
        if(swith){
            $(this).val(returnCitySN.cname).attr('id','sel_city');
            var s1 = document.createElement('script'),s2 = document.createElement('script'),s3 = document.createElement('script');
            s1.setAttribute('src','/index/js/city.js');
            s2.setAttribute('src','/index/js/picker.min.js');
            s3.setAttribute('src','/index/js/city-js.js');
            $('.js').after(s1,s2,s3);
        }
        swith = false;
    });

    $('button').on('click',function(){
        var $this = $(this), $self = $this.closest('dl').closest('div'), $next = $self.next(), $value = "", $H = $(window).height();
        switch( $self.attr('rel') ){
            case 'button':
                $value = $this.attr('rel') ? $this.attr('rel') : $this.text();
                break;
            case 'textarea':
                var $textarea = $self.find('textarea'), $title = $textarea.attr('title');
                $value = $textarea.val();
                if ( ( $value == $title ) || $value == "" ){
                    $("#hint").text('请正确填写'+$textarea.attr('rel')+'！')
                    $("#hint").fadeIn().stop(true,true).fadeOut(1500);
                    $textarea.focus();
                    return false;
                }
                if ($textarea.attr('id') === "mobile"){
                    var reg = /^[1][3-8][0-9]{9}$/; //只允许使用数字-空格等
                    if(!reg.test($value)){
                        $("#hint").text('请正确填写'+$textarea.attr('rel')+'！');
                        $("#hint").fadeIn().stop(true,true).fadeOut(1500);
                        $textarea.focus();
                        return false;
                    }
                }
                break;
            case 'input':
                const $input = $self.find('input'),$tit = $input.attr('title');
                $value = $input.val();
                if( ( $tit == $value ) || $value == "" ){
                    $("#hint").text('请正确填写'+$input.attr('rel')+'！')
                    $("#hint").fadeIn().stop(true,true).fadeOut(1500);
                    return false;
                }
                break;
        }
        $this.parent().find('button').removeClass('active');
        $this.addClass('active');

        if ( $self.next('div').length > 0 ){
            $self.next().show();
            //  性别
            if ( $(this).hasClass("js_sex_button") ){
                $('input[id=p8]').val($(this).attr('rel'));
            }
            // 通过div name匹配input ID 名
            $('input#'+$self.attr('name')).val($value);

            var height = 0;
            if(($H - $next.height()) < 0){
                $('#fill').height(0);
            }else{
                $('#fill').height($H - $next.height());
            }
            var top = $self.next().find('.clear').offset().top;
            var top = Math.ceil(top);
            $('html,body').animate({scrollTop: top}, 500);
        }
    });

    // 日期选择
    $(function(){
        new Mdate("dateShow", {
            acceptId: "dateShow",
            beginYear: "1948",
            beginMonth: "1",
            beginDay: "1",
            endYear: "2000",
            endMonth: "1",
            endDay: "1",
            format: "-"
        });
    });
    /*输入框获得焦点事件*/
    $('textarea').on('focus',function(){
        var $title = $(this).attr('title'), $value = $(this).val();
        if ( $title == $value ){
            $(this).val('').css('color','#000');
        }
    });
    /* 输入框失去焦点事件*/
    $('textarea').on('blur',function(){
        var $title = $(this).attr('title'), $value = $(this).val();
        if ( $value == "" ){
            $(this).val($title).css('color','#aaa');
        }
    });



    //模拟后台数据
    var arr=[];
    $("#mobile_end").click(function(){
        // $("#f1").find('input').each(function(i){
        //     arr.push($('input[type=hidden]').eq(i).val());
        // });
        // localStorage.setItem("site",JSON.stringify(arr));
        // window.location.href="";
        $('#f1').submit();
    });

</script>
</html>