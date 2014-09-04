@extends('v2.layouts.master')

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
			{{ View::make('v2.users.forms.login') }}
		</div>
		<aside class="login-disclaimer">
			Read our <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a> page.	
		</aside>
		<div class="clearfix"></div>
	</div>
@stop