<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>家庭风险测评</title>
    @include('index.public.css')
    
</head>
<body style="background:#fff;">
<div id="resule" class="flexv wrap">
    <div class="head">
        <div class="between top">
            <div class="flexv left-t">
                <h4>风险评估报告</h4>
                <p class="date"></p>
            </div>
            <a href="{{ route('family_appraisal', $message->user_id) }}" class="flex center right-t">重测</a>
        </div>
        <div class="flexv center chart">
            <canvas id="chart"></canvas>
            <p>健康风险</p>
        </div>
    </div>
    <div class="tbtext">
        <div class="tex"></div>
    </div>
    <p class="flex center sex"></p>
    <p class="flex center caption">中国重大疾病经验发生率表</p>
    <!--曲线图-->
    <div class="chart_excel">
        <div id="main" style="height:100%;"></div>
        <div class="flexv center xians">
            <p class="age"></p>
            <p class="percent"></p>
        </div>
    </div>
    <!--标题-->
    <div class="flex center subhead">
        <span class="s-age"></span>岁<span class="s-sex"></span>性易发重疾
    </div>
    <!--易发重疾显示-->
    <div class="nessbox">
        <div class="around nessbox_t">
            <div class="flexv center nessbox1">
                <img src='' style="display: block"/>
                <span></span>
            </div>
            <div class="flexv center nessbox2">
                <img src='' style="display: block"/>
                <span></span>
            </div>
            <div class="flexv center nessbox3">
                <img src='' style="display: block"/>
                <span></span>
            </div>
        </div>
    </div>
    <!--咨询按钮-->
    <div class="flex center zx-btn">
        <a href="javascript:;" class="flex center">咨询健康专家</a>
    </div>
    <!--弹窗提示-->
    <div id="wechat" class="alert">
        <div class="mask"></div>
        <div class="content wechat">
            <h3 class="flex center">加我免费咨询</h3>
            <div class="qrcode">
                <img src="{{ $message->user->qrcode ? $message->user->qrcode : "/kf_qrcode.jpg" }}" class="fitimg">
            </div>
            <p class="flex center">长按识别二维码</p>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/echarts/4.0.0/echarts.min.js"></script>
<script src="/index/js/picker.min.js"></script>
<script src="/index/js/echarts.js"></script>
<script type="text/javascript">
    // 获取数据
    let a = "{{ $message->age }}";
        matter = "{{ $message->type }}",  //事项
        place = "{{ $message->region }}",  //地点
        name = "{{ $message->name }}",  //名字
        tel = "{{ $message->phone }}",  //电话
        family = "{{ $message->family }}",  //家庭
        age = a.substring(-1,4),  //年龄
        income = "{{ $message->income }}",  //收入
        sex = {{ $message->gender }}>0 ? '男':'女',  //性别
        deg = 0,  //进度
        gzd = ''; //健康等级

    $(".sex").text(`${sex}性25种重疾发病率`);
    $(".subhead .s-sex").text(sex);
    // 当前日期
    function getDate() {
        let date = new Date();
        let year = date.getFullYear();
        let month = date.getMonth() + 1;
        let strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        let currentdate = year + '-' + month + '-' + strDate;
        return currentdate;
    }
    $(".left-t .date").text(getDate());
    // 健康状况||当前年龄
    let date = new Date,year = date.getFullYear(),yx = parseInt(year)-parseInt(age);
    if(yx >= 45){
        deg = 75;
        gzd = '高';
        $(".tbtext .tex").text('45岁后，重大疾病的发病率明显提升，没有健康的基石，看起来安稳的幸福也是脆弱的。');
    }
    if(yx >= 31 && yx <= 45){
        deg = 50;
        gzd = '中';
        $(".tbtext .tex").text('30岁后，我们的身体衰老加速，拼搏事业、照顾家庭的同时别忘了多多关心自身的健康。');
    }
    if(yx >= 18 && yx <= 30){
        deg = 25;
        gzd = '低';
        $(".tbtext .tex").text('年轻就是身体的本钱，但也要加强运动！因为在30岁以后，身体机能开始下滑，需要未雨绸缪哦。');
    }
    // 健康测评圆形图
    $(function () {
        var can = document.getElementById('chart'), ctx = can.getContext('2d');
            can.width = $(document).width()/2,
            can.height = $(document).height()/6;
        var cenX = can.width/2,
            cenY = can.height/2,
            rad = Math.PI*2/100;
        //绘制白色外圈
        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 12; //设置线宽
        ctx.strokeStyle = "#96c6ff";
        ctx.arc(cenX, cenY, 40, 0, Math.PI*2, false);
        ctx.stroke();
        //绘制运动外圈
        ctx.save();
        ctx.beginPath();
        ctx.lineWidth = 12;
        ctx.strokeStyle='#fff9ac';
        ctx.arc(cenX, cenY, 40, -Math.PI/2, -Math.PI/2+deg*rad, false);
        ctx.stroke();
        // 绘制文字
        ctx.save();
        ctx.font = "20px Arial"; //设置字体大小和字体
        ctx.fillStyle = "#fff600"; //设置描边样式
        //绘制字体，并且指定位置
        ctx.textAlign = "center";
        ctx.fillText(gzd, cenX, cenY+8);
        ctx.stroke(); //执行绘制
        ctx.restore();
        // 绘制曲线图
        initEchart(sex);
    });
    // 标题年龄段
    if(yx >= 18 && yx <= 29){
        $(".subhead .s-age").text('18~29');
    }
    if(yx >= 30 && yx <= 39){
        $(".subhead .s-age").text('30~39');
    }
    if(yx >= 40 && yx <= 49){
        $(".subhead .s-age").text('40~49');
    }
    if(yx >= 50 && yx <= 59){
        $(".subhead .s-age").text('50~59');
    }
    if(yx >= 60 && yx <= 70){
        $(".subhead .s-age").text('60~70');
    }
    // 曲线图表
    function initEchart(sex) {
        if(sex == '男'){
            var xdata = echartsman.age,ydata = echartsman.pro;
            let perc = parseFloat(echartsman.pro[echartsman.age[yx]]).toFixed(2);
            $(".xians .age").text(`${yx}岁`);
            $(".xians .percent").text(`${perc}%`);
            //男性重疾显示
            if(yx >= 18 && yx <= 29){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/leukemia.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('白血病');
                $(".nessbox3").children('span').text('脑血管病');
            }
            if(yx >= 30 && yx <= 39){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/gan.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('肝癌');
                $(".nessbox3").children('span').text('脑血管病');
            }
            if(yx >= 40 && yx <= 49){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/gan.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('脑血管病');
                $(".nessbox3").children('span').text('肝癌');
            }
            if(yx >= 50 && yx <= 59){
                $(".nessbox1").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/gan.jpg');
                $(".nessbox1").children('span').text('脑血管病');
                $(".nessbox2").children('span').text('心脏病');
                $(".nessbox3").children('span').text('肝癌');
            }
            if(yx >= 60 && yx <= 70){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/fei.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('肺癌');
                $(".nessbox3").children('span').text('脑血管病');
            }
        }else{
            var xdata = echartswoman.age,ydata = echartswoman.pro;
            let perc = parseFloat(echartswoman.pro[echartswoman.age[yx]]).toFixed(2);
            $(".xians .age").text(`${yx}岁`);
            $(".xians .percent").text(`${perc}%`);
            //女性重疾显示
            if(yx >= 18 && yx <= 29){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/leukemia.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/nerve.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('白血病');
                $(".nessbox3").children('span').text('神经系统疾病');
            }
            if(yx >= 30 && yx <= 39){
                $(".nessbox1").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/xr.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox1").children('span').text('心脏病');
                $(".nessbox2").children('span').text('乳腺癌');
                $(".nessbox3").children('span').text('脑血管病');
            }
            if(yx >= 40 && yx <= 70){
                $(".nessbox1").children('img').attr('src','/index/image/brain.jpg');
                $(".nessbox2").children('img').attr('src','/index/image/heart.jpg');
                $(".nessbox3").children('img').attr('src','/index/image/fei.jpg');
                $(".nessbox1").children('span').text('脑血管病');
                $(".nessbox2").children('span').text('心脏病');
                $(".nessbox3").children('span').text('肺癌');
            }
        }
        var myChart = echarts.init(document.getElementById("main"));
        let option = {
            title: {
                show: false
            },
            tooltip: {
                trigger: 'axis',
                show: false,
                axisPointer: {
                    type: 'cross',
                    label: {
                        backgroundColor: '#e8f5fd'
                    }
                }
            },
            toolbox: {
                show: false,
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '2%',
                right: '4%',
                bottom: '5%',
                top: '4%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: xdata,
                    axislabel: {show: false},
                    axisline: {show: false},
                    axisLine: {
                        lineStyle: {
                            color: '#c2cdd3'
                        }
                    },
                    //隐藏坐标轴刻度点
                    axisTick: {
                        show: false,
                        length: 10
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    min: 0,
                    max: 4,
                    axisLine: {
                        lineStyle: {
                            color: '#c2cdd3'
                        }
                    },
                    axisLabel: {
                        formatter: '{value} %'
                    },
                    //隐藏坐标轴刻度点
                    axisTick: {
                        show: false
                    }
                }
            ],
            series: [
                {
                    name: '发病率',
                    type: 'line',
                    stack: '总量',
                    //拐点!!!!大小
                    symbolSize: 0,
                    itemStyle: {
                        normal: {
                            color: '#e8f5fd'
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 1,  //连线粗细
                            color: "#0087e8"  //连线颜色
                        }
                    },
                    areaStyle: {normal: {}},
                    data: ydata
                }
            ]
        }
        myChart.setOption(option, true);
    }

    // 弹窗
    $(".zx-btn a").click(function () {
        $("#wechat").show().find(".content").addClass('trans');
    });
    $(".mask").click(function(){
        $(".alert").hide();
    });
</script>
</html>