@extends('layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
@stop

@section('title')
Forgot Password
@stop

@section('content')
	<div class="forgot-container">
		<h2>forgot your password?</h2>
		{{ Confide::makeForgotPasswordForm()->render() }}
	</div>
@stop
