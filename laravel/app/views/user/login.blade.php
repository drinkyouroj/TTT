@extends('layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
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
		</div>
		<aside class="login-disclaimer">
			Disclaimer here.	
		</aside>
	</div>
	<div class="col-md-4  signup-container">
		<h2>Signup</h2>
		<div class="signup-form">
		{{ Confide::makeSignupForm()->render() }}
		</div>
		<aside class="explanation">
			<p>Explantion for the signup process.  Potentially put some disclaimer here or something.</p>
		</aside>
	</div>
@stop
