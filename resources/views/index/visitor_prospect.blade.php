<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>准客户</title>
    @include('index.public.css')
</head>
<body class="mescroll">
<div id="prospect" class="flexv">
    <div class='flexitemv mainbox' id="mainbox">

    </div>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.0.0/jquery.min.js"></script>

@include('index.public._page', ['mescroll_id' => 'body', 'tip' => '', 'html' => 'mainbox', 'route' => route('visitor_prospect'), 'lists' => $lists, 'lazyload' => 0])
</html>