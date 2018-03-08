<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>提现明细</title>
    <link rel="stylesheet" href="http://xhh.wasd1.cn/static/css/base.css">
    <link rel="stylesheet" href="../index/css/index.css">
</head>
<body>
<div id="partner" class="flexv mainbox wrap">
    <div class="flexv partner-container">
        <div class="between theme-column">
            <div class="flex center theme">今日总收入</div>
            <div class="flex center theme">{{ number_format(($extension_today[2] + $extension_today[5]), 2) }}元</div>
        </div>
        <ul class="today-datum-list">
            <li class="between">
                <div class="flex center title sub-title">我的推广奖金({{ $user->integral_scale ? $user->integral_scale :30 }}%×付费金额)</div>
                <div class="flex center datum-cont">{{ number_format($extension_today[2], 2) }}元</div>
            </li>
            <li class="between">
                <div class="flex center title">推广人数</div>
                <div class="flex center datum-cont">{{ $extension_today[0] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费人数</div>
                <div class="flex center datum-cont">{{ $extension_today[1] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费金额</div>
                <div class="flex center datum-cont">{{ number_format($extension_today[2], 2) }}元</div>
            </li>
        </ul>
        <ul class="today-datum-list">
            <li class="between">
                <div class="flex center title sub-title">下级推广抽成(10%×付费金额)</div>
                <div class="flex center datum-cont">{{ number_format($extension_today[5], 2) }}元</div>
            </li>
            <li class="between">
                <div class="flex center title">推广人数</div>
                <div class="flex center datum-cont">{{ $extension_today[3] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费人数</div>
                <div class="flex center datum-cont">{{ $extension_today[4] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费金额</div>
                <div class="flex center datum-cont">{{ number_format($extension_today[5], 2) }}元</div>
            </li>
        </ul>
    </div>

    <div class="partner-container" style="margin-top:28px;">
        <div class="between theme-column">
            <div class="flex center theme">累计总收入</div>
            <div class="flex center theme">{{ number_format(($extension_all[5] + $extension_all[2]), 2) }}元</div>
        </div>
        <ul class="today-datum-list">
            <li class="between">
                <div class="flex center title sub-title">我的推广奖金({{ $user->integral_scale ? $user->integral_scale :30 }}%×付费金额)</div>
                <div class="flex center datum-cont">{{ number_format($extension_all[2], 2) }}元</div>
            </li>
            <li class="flex">
                <a href="{{ route('extension_list', 'user') }}" class="between">
                    <div class="flex center title">推广人数</div>
                    <div class="flex center title">
                        <div class="datum-cont color">{{ $extension_all[0] }}人</div>
                        <div class="icon-arrow color">&gt;</div>
                    </div>
                </a>
            </li>
            <li class="flex">
                <a href="{{ route('extension_list', 'order') }}" class="between">
                    <div class="flex center title">付费人数</div>
                    <div class="flex center title">
                        <div class="datum-cont color">{{ $extension_all[1] }}人</div>
                        <div class="icon-arrow color">&gt;</div>
                    </div>
                </a>
            </li>
            <li class="between">
                <div class="flex center title">付费金额</div>
                <div class="flex center datum-cont">{{ number_format($extension_all[2], 2) }}元</div>
            </li>
        </ul>
        <ul class="today-datum-list">
            <li class="between">
                <div class="flex center title sub-title">下级推广抽成(10%×付费金额)</div>
                <div class="flex center datum-cont">{{ number_format($extension_all[5], 2) }}元</div>
            </li>
            <li class="between">
                <div class="flex center title">推广人数</div>
                <div class="flex center datum-cont">{{ $extension_all[3] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费人数</div>
                <div class="flex center datum-cont">{{ $extension_all[4] }}人</div>
            </li>
            <li class="between">
                <div class="flex center title">付费金额</div>
                <div class="flex center datum-cont">{{ number_format($extension_all[5], 2) }}元</div>
            </li>
        </ul>
    </div>
</div>
</body>
<script type="text/javascript">
  
</script>
</html>