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
	<div class="col-md-4 col-md-offset-4 login-container">
		<div class="login-form">
			<h2>Login</h2>
			{{ Confide::makeLoginForm()->render() }}
		</div>
		<aside class="login-disclaimer">
			Disclaimer here.	
		</aside>
		<div class="clearfix"></div>
	</div>
	
@stop
