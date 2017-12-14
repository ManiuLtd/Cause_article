{layout name='admin/layout' /}

<style>
    label, .lbl{
        vertical-align: baseline;
    }
</style>
<div class="col-xs-12">
    <div class="page-header">
        <h1>{:v('headtitle')}</h1>
    </div>
    <form class="form-horizontal" id="form" onsubmit="false" role="form" action="{:url('webWechat')}">
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> token </label>

            <div class="col-sm-9">
                <input type="text" name="token" class="col-xs-10 col-sm-5" value="{:v('wechat.token')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">微信首页验证时使用的token</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> appid </label>

            <div class="col-sm-9">
                <input type="text" name="appid" class="col-xs-10 col-sm-5" value="{:v('wechat.appid')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">公众号身份标识</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> appsecret </label>

            <div class="col-sm-9">
                <input type="text" name="appsecret" class="col-xs-10 col-sm-5" value="{:v('wechat.appsecret')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">公众平台API(参考文档API 接口部分)的权限获取所需密钥Key</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> mch_id </label>

            <div class="col-sm-9">
                <input type="text" name="mch_id" class="col-xs-10 col-sm-5" value="{:v('wechat.mch_id')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">公众号支付请求中用于加密的密钥Key,微信发来的邮件中的微信支付商户号</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> key </label>

            <div class="col-sm-9">
                <input type="text" name="key" class="col-xs-10 col-sm-5" value="{:v('wechat.key')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">商户支付密钥,此值需要手动在腾讯商户后台API密钥保持一致</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right"> encodingaeskey </label>

            <div class="col-sm-9">
                <input type="text" name="encodingaeskey" class="col-xs-10 col-sm-5" value="{:v('wechat.encodingaeskey')}"/>
                <span class="help-inline col-xs-12 col-sm-7">
                    <span class="middle">开发者手动填写或随机生成，将用作消息体加解密密钥</span>
                </span>
            </div>
        </div>
        <div class="space-4"></div>

        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="button" id="submit">
                <i class="icon-ok bigger-110"></i>
                添加
            </button>

            <button class="btn" type="reset">
                <i class="icon-undo bigger-110"></i>
                清空
            </button>
        </div>
    </form>
</div>