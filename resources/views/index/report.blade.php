<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <title>举报/报错</title>
    @include('index.public.css')
</head>
<body>
<div id="report" class="flexv wrap">
    <div class="flexitemv mainbox">
        <div class="flex centerv padd title">请选择举报 / 报错问题</div>
        <div class="box">
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'内容格式错误']) }}" class="between listbox">
                <em class="flex centerv list">内容格式错误</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'含色情内容']) }}" class="between listbox">
                <em class="flex centerv list">含色情内容</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'含政治敏感信息']) }}" class="between listbox">
                <em class="flex centerv list">含政治敏感信息</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'包含谣言信息']) }}" class="between listbox">
                <em class="flex centerv list">包含谣言信息</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'含暴力敏感信息']) }}" class="between listbox">
                <em class="flex centerv list">含暴力敏感信息</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'侵权']) }}" class="between listbox">
                <em class="flex centerv list">侵权</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            <a href="{{ route('report_text',['article_id'=>request()->article_id,'atype'=>request()->type,'type'=>'其他']) }}" class="between listbox">
                <em class="flex centerv list">其他</em>
                <i class="flex centerv bls bls-yjt"></i>
            </a>
            
        </div>
    </div>
</div>
</body>
</html>