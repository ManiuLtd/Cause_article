<div class="flex tabbars">
    <div class="flexitem center middle">
        <a href="{{route('index.index')}}" class="flexv center user">
				<span class="flex userimg">
					<img class="fitimg" src="{{session()->get('head_pic')}}"/>
				</span>
            <em class="flex center">首页</em>
        </a>
    </div>
    <a href="{{route('visitor_record')}}" class="flexv center item">
        <i class="flex center bls bls-fkjl @if(session()->has('newkf')) fk @endif"></i>
        <em class="flex center">访客记录</em>
    </a>
    <a href="{{route('open_member')}}" class="flexv center item">
        <i class="flex center bls bls-zfkt"></i>
        <em class="flex center">支付开通</em>
    </a>
    <a href="{{route('index.user')}}" id="data" class="flexv center item">
        <i class="flex center bls bls-grzx"></i>
        <em class="flex center">个人中心</em>
    </a>
</div>