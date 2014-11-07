@extends('v2.layouts.master')


@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/users/signup_login.css?v={{$version}}" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/user/signup_form.js?v={{$version}}"></script>
	<script type="text/javascript">
		window.disable_signup =1;
	</script>
@stop

@section('title')
Sign up! | Sondry
@stop

@section('content')
	<div class="container">
		<div class="col-sm-6 col-sm-offset-3 signup-container">
			<div class="signup-form">
				<h2>Signup</h2>
				{{ View::make('v2.users.forms.signup') }}
				<div class="terms-agree">
					<div class="email-subtext">
						* Emails are optional, you'll need it if you forget your password.
					</div>
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
