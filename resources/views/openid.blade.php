<html lang="en">
<head>
    <meta charset="utf-8">
    <title>获取 openid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="renderer" content="webkit">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta content="telephone=no,email=no" name="format-detection">
    <meta name="App-Config" content="fullscreen=yes,useHistoryState=yes,transition=yes">
    <style>
        .btn {
            color: #fff;
            background-color: #67c23a;
            border-color: #67c23a;
            width: 26vw;
            height: 9vw;
            margin-top: 5vw;
            border-radius: 1vw;
        }
        p {
            line-height: 2;
            border: 1px solid red;
            padding: 1vw;
        }
        .flex{display: -webkit-box; height: 100%;}
        .flexitemv{display: -webkit-box; -webkit-box-flex: 1; -webkit-flex: 1; flex: 1; -webkit-box-orient: vertical;}
        .center{-webkit-box-align: center; -webkit-box-pack: center;}
    </style>
</head>
<body class="flex">
<div class="flexitemv center">
    <p>{{ $openid }}</p>
    <button type="button" class="btn" data-clipboard-text="{{ $openid }}">复制</button>
</div>
<script src="https://cdn.bootcss.com/clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
  var clipBoard =  new Clipboard('.btn');

  clipBoard.on('success', function(e) {
    e.clearSelection();
    alert('复制成功');
  });

  clipBoard.on('error', function(e) {
    alert('复制失败');
  });
</script>
</body>
</html>