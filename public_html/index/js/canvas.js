// 高清处理
var pixelRatio = function(ctx) {
    var dpr = window.devicePixelRatio || 1,
        bsr = ctx.webkitBackingStorePixelRatio ||
            ctx.mozBackingStorePixelRatio ||
            ctx.msBackingStorePixelRatio ||
            ctx.oBackingStorePixelRatio ||
            ctx.backingStorePixelRatio || 1;
    return dpr / bsr;
};

/**
 * 图像处理
 * @param url  图片地址
 * @param callback  回调函数
 * @param wo  图片的位置及宽高
 */
function preImage(url,callback,wo) {
    var img = new Image(); //创建一个Image对象，实现图片的预下载
    img.src = url;
    if(img.complete){ // 如果图片已经存在于浏览器缓存，直接调用回调函数
        callback.call(img,wo.x,wo.y,wo.width,wo.height);
        return; // 直接返回，不用再处理onload事件
    }
    img.onload = function () {  //图片下载完毕时异步调用callback函数。
        callback.call(img,wo.x,wo.y,wo.width,wo.height);  //将回调函数的this替换为Image对象
    }
}

/**
 * 控制文字
 * @param t    t需要生成的文字
 * @param x    x坐标位置
 * @param y    y坐标位置
 * @param w    w文本展示宽度
 * @param z    z文本字体大小
 * @param c    c文本字体颜色
 */
function drawText(t, x, y, w, z, c) {
    var chr = t.split("");
    var temp = "";
    var row = [];
    ctx.font = z + "px Arial";
    ctx.fillStyle = c;
    ctx.textBaseline = "middle";
    for (var a = 0; a < chr.length; a++) {
        if (ctx.measureText(temp).width > w) {
            row.push(temp);
            temp = "";
        }
        temp += chr[a];
    }
    row.push(temp);
    for (var b = 0; b < row.length; b++) {
        ctx.fillText(row[b], x, y + (b + 1) * 20);
    }
}

/**
 * 把图片处理成圆形,如果不是正方形就按最小边一半为半径处理
 * @param  {[type]} img 图片(img)对象或地址
 * @return {[type]}     return base64 png图片字符串
 */
function circleImg(url) {
    if (typeof url !== 'object') {
        var img = new Image();
        img.src = url;
    }
    var w, h, _canv, _contex, cli;
    if (img.width > img.height) {
        w = img.height;
        h = img.height;
    } else {
        w = img.width;
        h = img.width;
    }
    _canv = document.createElement('canvas');
    _canv.width = w;
    _canv.height = h;
    _contex = _canv.getContext("2d");
    cli = {
        x: w / 2,
        y: h / 2,
        r: w / 2
    };
    _contex.clearRect(0, 0, w, h);  //在给定的矩形内清除指定的像素
    _contex.save();   //保存当前环境的状态
    _contex.beginPath();  //重置当前路径
    _contex.arc(cli.x, cli.y, cli.r, 0, 2 * Math.PI, false); //创建圆形 x,y为圆心坐标,r为半径 False:顺时针，true:逆时针
    _contex.clip();  //剪切
    _contex.drawImage(img, 0, 0);  //绘制图像
    _contex.restore();  //还原之前属性
    return _canv.toDataURL();
}
