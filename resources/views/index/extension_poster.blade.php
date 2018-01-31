<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>增员海报</title>
    @include('index.public.css')
</head>
<body>
<div id="posters" class="flexv wrap">
    <div class="flexitemv mainbox">
        <div class="flex centerv edit"><i class="flex center bls bls-amend"></i><span>修改个人信息</span></div>
        <div class="flexv posterbox">
            <p class="flex center">图片已生成，长安保存后即可分享到朋友圈</p>
            <div class="flex poster">
                <i class="flex center bls bls-zjt_"></i>
                <img class="img" src="/poster.jpg" style="width: 23rem;">
                <i class="flex center bls bls-zjt_"></i>
            </div>
            <button class="flex center share-btn" type="button">去分享</button>
        </div>
        <div class="listbox">
            <div class="between title">
                <div class="flex center text"></div>
                <div class="flex centerv change"><i class="flex center bls bls-change"></i>换一批</div>
            </div>
            <ul class="fwrap list">
                <li class="item">
                    <a href="javascript:;" class="flex link">
                        <img src="/poster.jpg" class="fitimg">
                    </a>
                    <p class="flex center name">早安-道路</p>
                </li>
                <li class="item">
                    <a href="javascript:;" class="flex link">
                        <img src="/poster.jpg" class="fitimg">
                    </a>
                    <p class="flex center name">早安-道路</p>
                </li>
            </ul>
        </div>
    </div>
    <!--提示-->
    <form class="flex center alert">
        <div class="mask"></div>
        <div class='content'>
            <i class="flex center bls bls-cuo cuo"></i>
            <!--<h3 class="flex center title">您的信息不完整</h3>-->
            <!--<p class="flex center tis">立刻完善资料，让客户找到您</p>-->
            <div class="flex center input">
                <span class="flex centerv">姓名</span>
                <input type="text" class="flexitem" placeholder="轩轩">
            </div>
            <div class="flex center input">
                <span class="flex centerv">手机号</span>
                <input type="text" class="flexitem" placeholder="138****3561">
            </div>
            <div class="flex centerv input brands">
                <span class="flex centerv">品牌</span>
                <input type="text" readonly="readonly" class="flexitem cenk" placeholder="安发国际">
                <i class="flex smtxt"></i>
                <i class="flex center bls bls-xia brand"></i>
            </div>
            <a href="javascript:;" class="flex center button">保存修改</a>
        </div>
    </form>
    <!--品牌-->
    <div id="brand" class="flexv dialog_box">
        <ul class="flexitemv mainbox company" style="padding-top: 20px">

        </ul>
        <ul class="lettrt">
            <li><a href="#">#</a></li>
            <li><a href="#A">A</a></li>
            <li><a href="#B">B</a></li>
            <li><a href="#C">C</a></li>
            <li><a href="#D">D</a></li>
            <li><a href="#E">E</a></li>
            <li><a href="#F">F</a></li>
            <li><a href="#G">G</a></li>
            <li><a href="#H">H</a></li>
            <li><a href="#I">I</a></li>
            <li><a href="#J">J</a></li>
            <li><a href="#K">K</a></li>
            <li><a href="#L">L</a></li>
            <li><a href="#M">M</a></li>
            <li><a href="#N">N</a></li>
            <li><a href="#O">O</a></li>
            <li><a href="#P">P</a></li>
            <li><a href="#Q">Q</a></li>
            <li><a href="#R">R</a></li>
            <li><a href="#S">S</a></li>
            <li><a href="#T">T</a></li>
            <li><a href="#U">U</a></li>
            <li><a href="#V">V</a></li>
            <li><a href="#W">W</a></li>
            <li><a href="#X">X</a></li>
            <li><a href="#Y">Y</a></li>
            <li><a href="#Z">Z</a></li>
        </ul>
    </div>

    <img src="{{ $head }}" class="hidden user">
    <img src="{{ $pic }}" class="hidden qrcode">
</div>
</body>
<script src="//cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
<script src="/index/js/brand.js"></script>
<script src="/index/js/canvas.js"></script>
<script type="text/javascript">
    var img =  document.querySelector(".img"),
        user = document.querySelector(".user"),
        qrcode = document.querySelector(".qrcode"),
        {{--src = "/uploads/{{ $photo->url }}",--}}
        src = "/poster.jpg",
        brand = "安然纳米",
        name = '小胡',
        phone = 15811111111;

    poster(src,brand,name,phone,qrcode);


    //修改个人信息
    $(".edit>span").click(function () {
        $(".alert").show();
    });

    //左
    $(".poster>i").first().click(function () {
        console.log($(this));
    });

    //右
    $(".poster>i").last().click(function () {
        console.log($(this));
    });

    //换一批
    $(".change").click(function () {
        console.log($(this));
    });



    function poster(src,brand,name,phone) {
        var can = document.createElement("canvas"),ctx = can.getContext("2d");
        var imgs = new Image();
        imgs.src = src;
        imgs.onload = function(){
            console.log(imgs.width, imgs.height);
            //设置画布尺寸
            can.width = imgs.width;
            can.height = imgs.height;
            //绘制背景图
            ctx.drawImage(imgs, 0, 0);
            ctx.fillStyle = 'rgba(0,0,0,0.5)';
            ctx.fillRect(0, can.height-160, can.width, can.height-160);
            ctx.drawImage(qrcode, can.width - 130, can.height - 130, 120, 120);
            //绘制用户头像
            ctx.save();
            ctx.strokeStyle = '#ccc';
            ctx.lineWidth = 2;
            ctx.arc(85, can.height - 85, 60, 0, 2 * Math.PI);
            ctx.stroke();
            ctx.clip();
            ctx.drawImage(user, 0, 0, user.width, user.height, 25, can.height - 145, 120, 120);
            ctx.restore();

            //绘制信息
            ctx.font = '32px Arial';
            ctx.fillStyle = '#fff';
            ctx.fillText(brand + name, 170, can.height - 100);
            ctx.fillText(phone, 170, can.height - 40);
            img.src = can.toDataURL('image/jpeg');
        }
    }

</script>
</html>