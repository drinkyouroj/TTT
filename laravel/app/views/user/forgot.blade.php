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
		<h2>Forgot your password?</h2>
		<p>Note, if you didn't input an e-mail address during signup</br> there is not a way to reset your password.</p>
		{{ Confide::makeForgotPasswordForm()->render() }}
	</div>
@stop
