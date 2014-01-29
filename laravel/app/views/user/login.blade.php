@extends('layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
@stop

@section('title')
Login
@stop

@section('content')
	<div class="col-md-4 col-md-offset-4 login-container">
		<div class="login-form">
			{{ Confide::makeLoginForm()->render() }}
		</div>
		<aside class="login-disclaimer">
			Disclaimer here.	
		</aside>
	</div>
@stop
