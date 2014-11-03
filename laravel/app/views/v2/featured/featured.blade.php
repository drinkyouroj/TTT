@extends('v2/layouts/master')
	<?php
		if(Auth::check()) {
			$user = Auth::user();
			$is_mod = $user->hasRole('Moderator');
			$is_admin = $user->hasRole('Admin');
		} else {
			$is_admin = false;
			$is_mod = false;
		}
		$is_guest = Auth::guest();
	?>

@section('js')

	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/featured/featured.js?v={{$version}}"></script>
	
	@if(Auth::check())
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post_actions.js"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/featured/logged_in.js"></script>		
	@endif

	@include('v2.partials.post-listing-template')

	<script type="text/javascript">
		@if(Auth::check())
			window.user_id = {{Auth::user()->id}};
		@endif
		//signup script shouldn't run on the featured page.
		window.disable_signup = 1;
	</script>
	
@stop

@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/featured/featured.css?v={{$version}}" rel="stylesheet" media="screen">

	{{--CSS and heading is the same so its not an issue to put that stuff here.--}}
	<meta name="description" content="Every life is a story waiting to be heard.">
	<meta property="og:title" content="Sondry" />
	<meta property="og:description" content="Every life is a story waiting to be heard." />
	<meta property="og:image" content="{{ Config::get('app.url').'/images/logos/sondry-icon.jpg' }}" />
	<meta property="og:type" content="article" />

	<meta name="twitter:title" content="Sondry">
	<meta name="twitter:description" content="Every life is a story waiting to be heard.">
	<meta name="twitter:image:src" content="{{ Config::get('app.url').'/images/logos/sondry-icon.jpg' }}">

@stop

@section('content')
<div class="featured-page">
	<div class="top-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 heading-title">
					<h1><img class="featured-logo" src="{{ URL::to('images/global/sondry-logo.png') }}" alt="SONDRY"></h1>
					<ul class="header-taglines list-inline">
						<li>Stories</li>
						<li>Live</li>
						<li>Here</li>
					</ul>
					{{--@if(!Auth::check())
						<div class="line"></div>
						<h3>For the Stories We Tell</h3>
						<p><a href="{{Config::get('app.url')}}/profile/{{$featured{0}->post->user->username}}" class="user-link">{{$featured{0}->post->user->username}}</a> and many other storymakers post on Two Thousand Times.</p>
						<div class="signup-btn">
							<a href="{{ URL::to( 'user/signup' ) }}" class="btn btn-flat-blue signup">Signup and Post Your Story</a>
						</div>
						<div class="login-header">
							- or  &nbsp;<a href="{{ URL::to( 'user/login' ) }}" class="login">Log in</a> -
						</div>
					@else
						<div class="line"></div>
						<h3 class="signed-in">welcome <a href="{{Config::get('app.url')}}/myprofile">{{ Session::get('username') }}</a></h3>
					@endif--}}
				</div>
				<div class="col-md-12 links-date">
					<div class="header-cat-links">
						<ul>
							<li class="popular">
								<a href="{{ URL::to('categories/all') }}">Popular</a>
							</li>
							<li class="new">
								<a href="{{ URL::to('categories/new') }}">New</a>
							</li>
						</ul>
					</div>
					<div class="date-circle">
						<span class="month">
							{{date('F')}}
						</span><br/>
						<span class="day">
							{{date('j')}}
						</span>
					</div>

				</div>
			</div>
		</div>

		
		
		<div class="clearfix"></div>
	</div>

	{{--We're caching the DB query right now for 10 minutes, but hopefully we'll get to caching the view--}}
	@include('v2.featured.featured-cached')

	@include('v2.featured.featured-trending')
	
</div>
@stop

