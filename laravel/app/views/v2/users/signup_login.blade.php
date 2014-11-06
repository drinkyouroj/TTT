@extends('v2.layouts.master')

@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/views/user.css?v={{$version}}" rel="stylesheet" media="screen">
@stop

@section('js')
<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/user/signup-form.js?v={{$version}}"></script>
	<script type="text/javascript">
		window.disable_signup =1;
	</script>
@stop

@section('title')
	Login or Signup  | Sondry
@stop

@section('content')
	<div class="col-md-4 col-md-offset-2 login-container">
		<div class="login-form">
			<h2>Login</h2>
			{{ View::make('v2.users.forms.login') }}
		</div>
	<div class="clearfix"></div>
	</div>
	<div class="col-md-4  signup-container">
		<h2>Signup</h2>
		<div class="signup-form">
			{{ View::make('v2.users.forms.signup') }}
		</div>
	<div class="clearfix"></div>
	</div>
@stop