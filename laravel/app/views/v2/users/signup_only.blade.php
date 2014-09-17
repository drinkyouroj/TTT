@extends('v2.layouts.master')


@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/users/signup_login.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/views/signup-form.js"></script>
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
				<div class="terms-agree">
					By creating an account you agree to our <a class="terms" href="{{Config::get('app.url')}}/terms">Terms Of Use</a> and <a class="terms" href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>.
					<br/>
		            Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
		            <br/>
		            <a class="contact" href="{{Config::get('app.url')}}/contact">Contact</a>
		        </div>
			</div>
			
		<div class="clearfix"></div>
		</div>
	</div>
@stop
