<!--提示-->
<form class="flex center alert" id="form" action="{{route('perfect_information', session('user_id'))}}">
    {{csrf_field()}}
    {{--<input type="hidden" name="id" value="{{session()->get('user_id')}}">--}}
    <div class="mask"></div>
    <div class='content user-info'>
        <i class="flex center bls bls-cuo cuo"></i>
        <h3 class="flex center title">您的信息不完整</h3>
        <p class="flex center tis">立刻完善资料，让客户找到您</p>
        <div class="flex center input">
            <span class="flex centerv">姓名</span>
            <input type="text" name="wc_nickname" class="flexitem" value="{{ $user->wc_nickname }}" data-rule="*" data-errmsg="请填写您的姓名">
        </div>
        <div class="flex center input">
            <span class="flex centerv">手机号</span>
            <input type="text" name="phone" class="flexitem" @if($user->phone) value="{{ $user->phone }}" @endif data-rule="m" data-errmsg="手机号码格式错误">
        </div>
        <div class="flex centerv input brands">
            <span class="flex centerv">品牌</span>
            <input type="text" readonly="readonly" class="flexitem cenk brand_name" placeholder="选择品牌" @if($user->brand_id) value="{{ $user->brand->name }}" @endif data-rule="*" data-errmsg="请选择您的品牌" onfocus="this.blur()">
            <input type="hidden" name="brand_id" class="brand_id" @if($user->brand_id) value="{{ $user->brand->id }}" @endif>
            <i class="flex smtxt"></i>
            <i class="flex center bls bls-xia brand"></i>
        </div>
        <a href="javascript:;" class="flex center button" id="submit">保存修改</a>
    </div>
</form>
<!--品牌-->
<div id="brand" class="flexitemv" data-id="{{ $user->brand ? $user->brand_id : '' }}" data-name="{{ $user->brand ? $user->brand->name : '' }}"></div>