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
            <input type="text" readonly="readonly" class="flexitem cenk" placeholder="选择品牌" @if($user->brand_id) value="{{ $user->brand->name }}" @endif data-rule="*" data-errmsg="请选择您的品牌" onfocus="this.blur()">
            <input type="hidden" name="brand_id" class="brand_id" @if($user->brand_id) value="{{ $user->brand->id }}" @endif>
            <i class="flex smtxt"></i>
            <i class="flex center bls bls-xia brand"></i>
        </div>
        <a href="javascript:;" class="flex center button" id="submit">保存修改</a>
    </div>
</form>
<!--品牌-->
<div id="brand" class="flexv dialog_box">
    <div class="flex center head">
        <a href="javascript:;" class="bls bls-zjt"></a>
        <h1 class="flexitem center" style="margin-left: -2rem;">选择品牌</h1>
    </div>
    <ul class="flexitemv mainbox company" style="padding-top: 20px">

    </ul>
    <ul class="flexitemv lettrt">
        <li class="flexitem center"><a href="#">#</a></li>
        <li class="flexitem center"><a href="#A">A</a></li>
        <li class="flexitem center"><a href="#B">B</a></li>
        <li class="flexitem center"><a href="#C">C</a></li>
        <li class="flexitem center"><a href="#D">D</a></li>
        <li class="flexitem center"><a href="#E">E</a></li>
        <li class="flexitem center"><a href="#F">F</a></li>
        <li class="flexitem center"><a href="#G">G</a></li>
        <li class="flexitem center"><a href="#H">H</a></li>
        <li class="flexitem center"><a href="#I">I</a></li>
        <li class="flexitem center"><a href="#J">J</a></li>
        <li class="flexitem center"><a href="#K">K</a></li>
        <li class="flexitem center"><a href="#L">L</a></li>
        <li class="flexitem center"><a href="#M">M</a></li>
        <li class="flexitem center"><a href="#N">N</a></li>
        <li class="flexitem center"><a href="#O">O</a></li>
        <li class="flexitem center"><a href="#P">P</a></li>
        <li class="flexitem center"><a href="#Q">Q</a></li>
        <li class="flexitem center"><a href="#R">R</a></li>
        <li class="flexitem center"><a href="#S">S</a></li>
        <li class="flexitem center"><a href="#T">T</a></li>
        <li class="flexitem center"><a href="#U">U</a></li>
        <li class="flexitem center"><a href="#V">V</a></li>
        <li class="flexitem center"><a href="#W">W</a></li>
        <li class="flexitem center"><a href="#X">X</a></li>
        <li class="flexitem center"><a href="#Y">Y</a></li>
        <li class="flexitem center"><a href="#Z">Z</a></li>
    </ul>
</div>