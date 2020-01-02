{{--<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">--}}
<link rel="stylesheet" href="{{asset('css/app.css')}}">

<style type="text/css">
	html{height: 100%;}
	body{background: linear-gradient(165deg, #c6eaf6 5%, #dff1f6 45%, #daedf5 55%, #abc8ee 100%)}
	footer, #mySidenav, .open-nav, .top-bar, .header, #toTop{display: none;}
	.form-control{background: none;}
	.form-control2:focus {color: #495057;background: #ebebeb;border-color: #80bdff;outline: 0;box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);	}
	.login-container .main-wraper{margin: 150px auto auto auto; background: #eff8ff; border:1px solid #c1e2f9; border-radius: 2px; padding: 25px 10px;}
	.login-container .main-wraper h2{margin: 20px 0 25px 0;font-size: 24px;line-height: 21px;text-align: center;color: #475c6a;}
	.login-container .main-wraper input,.login-container .main-wraper select{border:0;margin:8px;padding:0;height:34px;color:#6c757d;text-align:center; font-size: 12px; }
	.login-container .main-wraper label{height:34px;color:#6c757d;text-align:center; font-size: 12px; width: 100%; }
	.login-container .main-wraper input:focus,.login-container .main-wraper select:focus{box-shadow: 0 0 0 .08rem rgba(0,123,255,.25) !important;}
	.submit{background: #0E92D2;color: #fff !important;padding: 8px;width: 100%;border-radius: 3px;}
	.error h4{ color: red; font-size: 14px; text-align: center; }
</style>
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<div class="login-container">
	<div class="col-md-3 main-wraper">
		<div class="col-md-12 circleThing">
			<img src="{{ asset('images/Logo.png') }}">
		</div>
		<form method="POST" action="{{ route('admin-login') }}" id="admin_login_form">
			@csrf
			<div class="col-md-12">
				<h2>{{ trans('label.impulsive_backend_login') }}</h2>
				<input type="hidden" name="user_type" value="0">
			</div>
			<div class="col-md-12">
				<input type="text" name="email" placeholder="{{ trans('label.username') }}" class="form-control">
				<input type="password" name="password" placeholder="{{ trans('label.password') }}" class="form-control">
				<label>German (Germany)</label>
			</div>
			<div class="col-md-12">
				<input type="submit" name="submit" value="{{ trans('label.login') }}" placeholder="{{ trans('label.login') }}" class="submit">
			</div>
		</form>
		@if($errors->any())
		  <div class="col-md-12 error"><h4>{{$errors->first()}}</h4></div>
		@endif
	</div>
</div>

<script src="{{ asset('js/backend.js') }}"></script>
