/**
 * 加载动画
 * @param str string 提示文字
 */
function showProgress(str) {
    var template = document.createElement('div');
    var loadingText = str ? '<div class="text">' + str + '</div>' : '';
    var str = '<div class="container"><div class="loader"></div>' + loadingText + '</div>';
    template.id = 'loading';
    template.innerHTML = str;
    document.body.appendChild(template);
}
/**
 * 隐藏加载动画
 */
function hideProgress() {
    var object = document.getElementById('loading');
    if (object) document.body.removeChild(object);
}
/**
 * 提示框
 * @param msg 描述
 * @param state 状态
 * @param dom 要插入到的元素
 * @param timeout 隐藏时间
 */
function showMsg(msg, state, dom, timeout) {
    hideProgress();
    var state = state || 0, timeout = timeout || 1000, dom = dom || 'body';
    var icon = '', bgColor = '', pos = dom == 'body' ? 'fixed' : 'absolute';
    if (state == 0) {
        bgColor = 'background:rgba(255,0,0,0.6)';
        icon = '<span style="position:absolute;top:19px;left:6px;right:6px;height:2px;background:#fff;transform:rotate(45deg);"></span><span style="position:absolute;top:19px;left:6px;right:6px;height:2px;background:#fff;transform:rotate(-45deg);"></span>';
    } else {
        bgColor = 'background:rgba(0,0,0,0.6)';
        icon = '<span style="position:absolute;top:18px;left:10px;width:24px;height:2px;background:#fff;transform:rotate(-45deg);"></span><span style="position:absolute;top:22px;left:2px;width:14px;height:2px;background:#fff;transform:rotate(45deg);"></span>';
    }
    var template = '<div id="msgBox" style="position:' + pos + ';top:50%;left:50%;width:160px;padding:10px;margin-left:-90px;' + bgColor + ';border-radius:4px;transform:scale(0);transition:transform 0.2s linear;z-index:999;">' +
        '<div class="icon" style="position:relative;width:36px;height:36px;border-radius:50%;border:2px solid #fff;margin:0 auto;">' + icon + '</div>' +
        '<div class="msg" style="padding:8px 0;text-align:center;color:#fff;">' + msg + '</div>' +
        '</div>';
    $(template).appendTo($(dom));
    setTimeout(function () {
        var ih = ($('#msgBox').height() + 20) / 2;
        $('#msgBox').css({'margin-top': -ih, 'transform': 'scale(1)'});
    }, 100);
    setTimeout(function () {
        $('#msgBox').remove();
    }, timeout + 100);
}
/**
 * actionSheet
 * @param title string 标题
 * @param btns json [{t:'标题',u:'链接'},{...}] 按钮列表
 * @param callback function 回调函数
 */
function actionSheet(title, btns, callback) {
    if (typeof btns != 'object' || $('#action').size() > 0) return;
    var names, theight, buttons = '';
    if (title == '') {
        names = '', theight = (btns.length + 1) * 50 + 10;
    } else {
        names = '<div class="title">' + title + '</div>', theight = (btns.length + 2) * 50;
    }
    for (var i = 0; i < btns.length; i++) {
        buttons += '<a href="javascript:;" data-url="' + btns[i].u + '">' + btns[i].t + '</a>';
    }
    buttons += '<a href="javascript:;" class="cancel">取消</a>';
    var template = '<div id="action"><div class="mask"></div><div class="button">' + names + '<div class="btns">' + buttons + '</div></div></div>';
    $('body').append(template);
    setTimeout(function () {
        $('#action .mask').css({'opacity': '0.6'}).next().css({
            'transform': 'translateY(-' + theight + 'px)',
            '-webkit-transform': 'translateY(-' + theight + 'px)'
        });
    }, 50);
    $('#action').click(function (e) {
        if (e.target.tagName == 'A' && typeof callback == 'function') {
            var index = $(e.target).index(), url = $(e.target).attr('data-url');
            callback(index, url);
        }
        $('#action .mask').css({'opacity': '0'}).next().css({
            'transform': 'translateY(0)',
            '-webkit-transform': 'translateY(0)'
        });
        setTimeout(function () {
            $('#action').remove();
        }, 200);
    });
}
/**
 * 弹窗组件
 * @param title string 标题
 * @param text string 内容
 * @param btns json [{},{}] 按钮
 * @param callback function 回调函数
 */
function showAlert(title, text, btns, callback) {
    var name = title != '' ? '<div class="title">' + title + '</div>' : '';
    var button = '';
    for (var h = 0; h < btns.length; h++) {
        var url = btns[h].u || '', w = 100 / btns.length;
        button += '<a href="javascript:;" class="btns" data-url="' + url + '" style="width:' + w + '%">' + btns[h].t + '</a>';
    }
    var template = '<div id="alert">' +
        '<div class="mask" style="position:absolute;top:0;left:0;width:100%;height:100%;"></div>' +
        '<div class="content">' +
        name +
        '<div class="text">' + text + '</div>' +
        '<div class="clearfix button">' + button + '</div>' +
        '</div>' +
        '</div>';
    $('body').append(template);
    var offset = {'height': $('#alert .content').height(), 'width': $('#alert .content').width()};
    $('#alert .content').css({
        'margin': parseInt(-offset.height / 2) + 'px 0 0 ' + parseInt(-offset.width / 2) + 'px',
        'transform': 'scale(0)',
        '-webkit-transform': 'scale(0)'
    });
    setTimeout(function () {
        $('#alert .content').css({
            'visibility': 'visible',
            'transform': 'scale(1)',
            '-webkit-transform': 'scale(1)'
        });
    }, 200);
    $('#alert .button a').click(function () {
        $('#alert').removeClass('show');
        setTimeout(function () {
            $('#alert').remove();
        }, 200);
        var index = $(this).index(), url = $(this).attr('data-url');
        if ('function' == typeof callback) callback(index, url);
    });
    $('#alert .mask').click(function () {
        $('#alert').removeClass('show');
        setTimeout(function () {
            $('#alert').remove();
        }, 200);
    });
}
/**
 * 图片自适应盒子
 * @param obj 图片对象
 */
function imageReady(obj) {
    var style = getComputedStyle(obj, '');
    if (style.position != 'absolute') return;
    var w = obj.parentNode.offsetWidth, h = obj.parentNode.offsetHeight;
    var boxScale = w / h, imgScale = obj.width / obj.height;
    if (boxScale >= 0) {
        // 盒子正方形
        if (parseInt(imgScale) <= 0) {
            // 图片宽小于等于高
            obj.width = w, obj.height = parseInt(w / imgScale);
            obj.style.top = Math.floor((h - obj.height) / 2) + 'px';
        } else {
            // 图片宽大于高
            obj.height = h, obj.width = parseInt(h * imgScale);
            obj.style.left = Math.floor((w - obj.width) / 2) + 'px';
        }
    } else {
        // 盒子宽小于高
        if (parseInt(imgScale) >= 0) {
            // 图片宽大于等于高
            obj.height = h, obj.width = parseInt(h * imgScale);
            obj.style.width = Math.floor((w - obj.width) / 2) + 'px';
        } else {
            // 图片宽小于高
            obj.width = w, obj.height = parseInt(w / imgScale);
            obj.style.top = Math.floor((h - obj.height) / 2) + 'px';
        }
    }
}
/**
 * 设置cookie
 * @parma name  cookie的名称
 * @param value cookie的值
 * @param time  cookie过期时间
 */
function setCookie(name, value, time) {
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + time * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toUTCString();
}
/**
 * 获取cookie
 * @parma name  cookie的名称
 */
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)) {
        return unescape(arr[2]);
    } else {
        return null;
    }
}
/**
 * 删除获取cookie
 * @parma name  cookie的名称
 */
function deleteCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null) document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}
/**
 * 短信验证码定时器
 * @param obj 发送按钮
 * @param html 设置文字
 * @param interval 间隔时间
 * @param cls 移除元素设置的class
 */
function smsTimer(obj, html, interval, cls) {
    obj.html(html + '(' + interval + ')');
    interval--;
    var time = setInterval(function () {
        if (interval == 0) {
            obj.attr('style', '').removeClass(cls).text(html);
            clearInterval(time);
        } else {
            obj.text(html + '(' + (interval--) + ')');
        }
    }, 1000);
}
/**
 * 获取链接的host
 */
function getHost(url) {
    var host = "null";
    if (typeof url == "undefined" || null == url)
        url = window.location.href;
    var regex = /.*\:\/\/([^\/]*).*/;
    var match = url.match(regex);
    if (typeof match != "undefined" && null != match)
        host = match[1];
    return host;
}
/**
 * get参数转 post参数
 * @param paramstr [附加参数]
 * @param url [get链接]
 */
function searchToJson(paramstr, url) {
    //if(paramstr == '') return {};
    var url = url || window.location.search;
    var newurl = url.indexOf('?') > -1 ? url.substr(url.indexOf('?') + 1) : '';
    var urlstr = (newurl == '') ? paramstr : newurl + '&' + paramstr;
    var arr = urlstr.split('&'), newarr = [];
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == '') continue;
        var strarr = arr[i].split('=');
        var arrstr = '"' + strarr[0] + '":"' + (strarr[1] || '') + '"';
        newarr.push(arrstr);
    }
    return JSON.parse('{' + newarr.join(',') + '}');
}