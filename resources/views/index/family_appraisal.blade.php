<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>家庭风险测评</title>
    <link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
    @include('index.public.css')
</head>
<body style="background:#fff">
<div id="appraisal" class="flexv wrap">
    <div class="head-img"><img src="/index/image/health.png" class="fitimg"></div>

    <div class="flex center btn"><a href="{{ route('family_appraisal_begin', request()->uid) }}" class="flex center">马上测评</a></div>

    <p class="flex center tex">已有<span>{{ $count + 1000 }}</span>人测评</p>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">

</script>
</html>