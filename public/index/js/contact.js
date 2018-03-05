var checkForm = function (config){
    this.defaultRule = {
        "*": /^[\w\W]+$/,
        "*6-16": /^[\w\W]{6,16}$/,
        "n": /^\d+$/,
        "m": /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$/,
        "e": /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
        "cname": /^[\u0391-\uFFE5]{2,15}$/,
        "idcard": /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
        "card" : /^\d{16,20}$/,
        "pwd" : /^[^\s]{6,16}$/,
        "domain" : /^[^\s]{3,6}$/
    };
    this.form = config.form || '#forms';
    this.btn = config.btn || '.submit';
    this.error = config.error || null;
    this.complete = config.complete || null;
    this.init();
};

checkForm.prototype.selector = function (selector,obj){
    var _ele = obj ? obj.querySelectorAll(selector) : document.querySelector(selector);
    return _ele;
};

checkForm.prototype.init = function (){
    var _this = this;
    var _btn = _this.selector(_this.btn),_form = _this.selector(_this.form);
    if(!_btn || !_form) return this;
    _btn.addEventListener('click',function (){
        var _formEle = _this.selector('input,select,textarea',_form);
        var _passed = true;
        for(var i = 0; i< _formEle.length; i ++){
            var _obj = _formEle[i],_rule = _obj.getAttribute('data-rule'),_disable = _obj.disabled,_sync = _obj.getAttribute('data-sync');
            if(_sync && _this.selector(_sync)){
                _rule = _this.selector(_sync).getAttribute('data-rule');
            }
            if(_rule && !_disable){
                var _ruleReg = _rule.indexOf('/') > -1 ? eval(_rule) : _this.defaultRule[_rule];
                if(_ruleReg){
                    var _errMsg = _obj.getAttribute('data-errmsg'),_val = _obj.value;
                    if(!_ruleReg.test(_val)){
                        _obj.focus();
                        if(_this.error) _this.error(_obj,_errMsg);
                        _passed = false;
                        break;
                    }
                    if(_sync && _this.selector(_sync)){
                        if(_obj.value != _this.selector(_sync).value){
                            _obj.focus();
                            if(_this.error) _this.error(_obj,_errMsg);
                            _passed = false;
                            break;
                        }
                    }
                }
            }
        }
        if(_passed && _this.complete) _this.complete(_form);
    },false);
};

checkForm.prototype.check = function (){
    if(arguments.length == 0) return false;
    var _this = this;
    for(var i = 0;i < arguments[0].length;i ++){
        var obj = arguments[0][i];
        var rule = obj.getAttribute('data-rule'),disable = obj.disabled,sync = obj.getAttribute('data-sync');
        if(sync && _this.selector(sync)) rule = _this.selector(sync).getAttribute('data-rule');
        if(sync && obj.value != _this.selector(sync).value && !disable){
            return obj.getAttribute('data-errmsg');
            break;
        }
        if(rule && !disable){
            var ruleReg = rule.indexOf('/') > -1 ? eval(rule) : _this.defaultRule[rule];
            if(ruleReg){
                var errMsg = obj.getAttribute('data-errmsg'),val = obj.value;
                if(!ruleReg.test(val)){
                    obj.focus();
                    return errMsg;
                    break;
                }
            }
        }
    }
};