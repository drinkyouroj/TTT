@extends('layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/signup-form.js"></script>
@stop

@section('title')
	Login or Signup
@stop

@section('content')
	<div class="col-md-4 col-md-offset-2 login-container">
		<div class="login-form">
			<h2>Login</h2>
			{{ Confide::makeLoginForm()->render() }}
			<div class="forgot-pw">
				<a href="{{Config::get('app.url')}}/user/forgot">forget your password?</a>
			</div>
		</div>
		<aside class="login-disclaimer">
			Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
		</aside>
	<div class="clearfix"></div>
	</div>
	<div class="col-md-4  signup-container">
		<h2>Signup</h2>
		<div class="signup-form">
		{{ Confide::makeSignupForm()->render() }}
		</div>
		<aside class="explanation">
			<p>Read our <a href="{{Config::get('app.url')}}/privacy">Privacy Policy</a> and <a href="{{Config::get('app.url')}}/terms">Terms of Use</a>.</p>
		</aside>
	<div class="clearfix"></div>
	</div>
@stop
