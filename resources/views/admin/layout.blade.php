<!DOCTYPE html>
<html lang="en">
@include('admin.public_css')
<style>
	.modal-dialog{
		margin-top: 250px;
	}
</style>
	<body>
	@include('admin.header')

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				@include('admin.sidebar')

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="/admin/index">首页</a>
							</li>
							<li class="active">{{v('headtitle')}}</li>
						</ul><!-- .breadcrumb -->
					</div>

					<div class="page-content">
						<div class="row">
							@if($_SERVER['REQUEST_URI'] == 'Index/index')
							<div class="col-xs-12">
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="icon-remove"></i>
									</button>
									<i class="icon-ok green"></i>
									管理后台
								</div>
							</div>
							@endif

							@yield('content')
						</div>
					</div>
				</div>
			</div>
			@include('admin.settings-container')
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div>
	@include('admin.public_js')
</body>

</html>

