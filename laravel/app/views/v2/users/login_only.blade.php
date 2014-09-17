@extends('v2.layouts.master')

@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/users/signup_login.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/views/signup-form.js"></script>
@stop

@section('title')
	Login or Signup
@stop

@section('content')
	<div class="container">
		<div class="col-md-8 col-md-offset-2 login-container">
			<div class="login-form">
				<h2>Login</h2>
				{{ View::make('v2.users.forms.login') }}
				 <div class="redirect-other">
		            Don't have an account? <a href="{{Config::get('app.url')}}/user/signup">Sign up now</a>
		        </div>
		        <div class="terms-agree">
		            Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
		            <br/>
		            <a class="contact" href="{{Config::get('app.url')}}/contact">Contact</a>
		           	| <a class="terms" href="{{Config::get('app.url')}}/terms">Terms Of Use</a> 
		           	| <a class="privacy" href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>
		        </div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@stop