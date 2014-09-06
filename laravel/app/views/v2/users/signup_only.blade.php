@extends('v2.layouts.master')


@section('css')
	<link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/signup-form.js"></script>
@stop

@section('title')
Sign up!
@stop


@section('content')
	<div class="col-md-4 col-md-offset-4 signup-container">
		<div class="signup-form">
		{{ View::make('v2.users.forms.signup') }}
		</div>
		
	<div class="clearfix"></div>
	</div>
@stop
