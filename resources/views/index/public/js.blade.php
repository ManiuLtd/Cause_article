<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    (function () {
        if (typeof WeixinJSBridge == "object" && typeof WeixinJSBridge.invoke == "function") {
            handleFontSize();
        } else {
            if (document.addEventListener) {
                document.addEventListener("WeixinJSBridgeReady", handleFontSize, false);
            } else if (document.attachEvent) {
                document.attachEvent("WeixinJSBridgeReady", handleFontSize);
                document.attachEvent("onWeixinJSBridgeReady", handleFontSize);
            }
        }
        function handleFontSize() {
            /*设置网页字体为默认大小*/
            WeixinJSBridge.invoke('setFontSizeCallback', {
                'fontSize': 0
            });
            /*重写设置网页字体大小的事件*/
            WeixinJSBridge.on('menu:setfont', function () {
                WeixinJSBridge.invoke('setFontSizeCallback', {
                    'fontSize': 0
                });
            });
        }
    })();
</script>