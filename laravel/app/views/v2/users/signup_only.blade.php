@extends('v2.layouts.master')


@section('css')
	<link href="{{Config::get('app.url')}}/css/compiled/v2/users/signup_login.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/signup-form.js"></script>
@stop

@section('title')
Sign up!
@stop


@section('content')
	<div class="container">
		<div class="col-md-6 col-md-offset-3 signup-container">
			<div class="signup-form">
				<h2>Signup</h2>
				{{ View::make('v2.users.forms.signup') }}
			</div>
			
		<div class="clearfix"></div>
		</div>
	</div>
@stop
