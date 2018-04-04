<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>付费人数</title>
    <link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
    @include('index.public.css')
    
</head>
<body>
<div id="pay" class="flexv mainbox wrap box mescroll">
    <ul class="flexv reg-container" id="reg-container">

    </ul>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'pay', 'tip' => '暂无用户付费', 'html' => 'reg-container', 'route' => route('extension_list', 'order'), 'lists' => $users, 'lazyload' => 0])

</html>