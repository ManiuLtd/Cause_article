<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>在线咨询</title>
    <link rel="stylesheet" type="text/css" href="/index/css/chatroom.css"/>
</head>
<body>
<div class="container">
  <div class="p1 hide clear" name="p1" rel="button" style="display:block;">
    <dl class="clear" style="padding-top:15px;">
      <dt>
      <div class="avatar"><img src="{{$res->user['head']}}" /></div>
      </dt>
      <dd>
        <div class="content">
          <p class="text">您好，我是{{$res->user['wc_nickname']}}，请问您想了解什么呢？</p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
    <dl class="right clear">
      <dt>
      <div class="avatar"><img src="{{session()->get('head_pic')}}" /></div>
      </dt>
      <dd>
        <div class="content button">
          <p class="text">
            <button rel="1">我想咨询健康问题</button>
            <button rel="2">了解加盟直销事业</button>
            <button rel="3">其他</button>
          </p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="p2 hide clear" name="p2" rel="button">
    <dl class="clear">
      <dt>
      <div class="avatar"><img src="{{$res->user['head']}}" /></div>
      </dt>
      <dd>
        <div class="content">
          <p class="text">您是？</p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
    <dl class="right clear">
      <dt>
      <div class="avatar"><img src="{{session()->get('head_pic')}}" /></div>
      </dt>
      <dd>
        <div class="content button">
          <p class="text">
            <button rel="50前后">50前后</button>
            <button rel="60后">60后</button>
            <button rel="70后">70后</button>
            <button rel="80后">80后</button>
            <button rel="90后">90后</button>
          </p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="p3 hide clear" name="p3" rel="textarea">
    <dl class="clear">
      <dt>
      <div class="avatar"><img src="{{$res->user['head']}}" /></div>
      </dt>
      <dd>
        <div class="content">
          <p class="text">怎么称呼您？</p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
    <dl class="right clear">
      <dt>
      <div class="avatar"><img src="{{session()->get('head_pic')}}" /></div>
      </dt>
      <dd>
        <div class="content button">
          <p class="text">
            <textarea rel="您的姓名" title="请填写您的姓名">请填写您的姓名</textarea>
            <button class="js_sex_button" rel="1">先生</button>
            <button class="js_sex_button" rel="2">女士</button>
          </p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="p4 hide clear" name="p4" rel="textarea">
    <dl class="clear">
      <dt>
      <div class="avatar"><img src="{{$res->user['head']}}" /></div>
      </dt>
      <dd>
        <div class="content">
          <p class="text">怎么联系到您？</p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
    <dl class="right clear">
      <dt>
      <div class="avatar"><img src="{{session()->get('head_pic')}}" /></div>
      </dt>
      <dd>
        <div class="content button">
          <p class="text">
            <textarea rel="您的手机号码" title="请填写您的手机号码" id="mobile">请填写您的手机号码</textarea>
            <button  id="mobile_submit">确定</button>
          </p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="p5 hide clear" name="p5" rel="end">
    <dl class="clear">
      <dt>
      <div class="avatar"><img src="{{$res->user['head']}}" /></div>
      </dt>
      <dd>
        <div class="content">
          <p class="text">收到，<img src="/index/image/smile.png" align="absmiddle" width="24" />我会尽快联系您的，请稍等</p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
    <dl class="right clear">
      <dt>
      <div class="avatar"><img src="{{session()->get('head_pic')}}" /></div>
      </dt>
      <dd>
        <div class="content button">
          <p class="text">
            <button id="mobile_end">结束</button>
          </p>
          <div class="arrow"></div>
        </div>
      </dd>
    </dl>
  </div>
  <form method="post" action="{{route('submit_message')}}" id="f1">
    {{csrf_field()}}
    <input type="hidden" name="type" id="p1" value="" />
    <input type="hidden" name="age" id="p2" value="" />
    <input type="hidden" name="name" id="p3" value="" />
    <input type="hidden" name="phone" id="p4" value="" />
    <input type="hidden" name="gender" id="p5" value="" />
    <input type="hidden" name="uid" value="{{$res->uid}}" />
    <input type="hidden" name="uaid" value="{{$res->id}}" />
  </form>
  
  <div id="fill" style="clear: both; float: left; visibility: hidden;"></div>
  <!--弹框提示-->
  <div id="hint"></div>
</div>
</body>
<script src="/index/js/jquery.min.js"></script>
<script type="text/javascript">
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
				$("#hint").fadeIn().stop(true,true).fadeOut(1500)
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
        }
        $this.parent().find('button').removeClass('active');
        $this.addClass('active');
		// 性别
        $('.js_sex_button').click(function(){
            $('input[name=gender]').val($(this).attr('rel'));
        });

        if ( $self.next('div').length > 0 ){
            $self.next().show();

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
            $(this).val($title).css('color','#AAA');
        }
    });
    //提交
    $('#mobile_end').click(function () {
        $('#f1').submit();
    })
</script>
</html>