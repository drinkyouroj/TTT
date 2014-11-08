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

	
	<div class="main-wrapper">
		<div class="main-container">
			<div class="row">
				
					@if(isset($featured{0}))
						<?php $main = $featured{0}->post; ?>
						@if(is_object($main))
					
							<div class="text col-md-4">
								<a href="{{Config::get('app.url')}}/posts/{{$main->alias}}">
									<h2>{{$main->title}}</h2>
									<div class="line"></div>
									<ul class="post-taglines list-inline">
										<li> {{ $main->tagline_1 }} </li>
										<li> {{ $main->tagline_2 }} </li>
										<li> {{ $main->tagline_3 }} </li>
									</ul>
								</a>
								<div class="author col-md-12">
									<?php $user_image = $main->user->image ? Config::get('app.imageurl').'/'.$main->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
									<a href="{{Config::get('app.url')}}/profile/{{$main->user->username}}"> 
										<span class="avatar" style="background-image:url({{$user_image}});"></span>
									</a>
									{{$main->story_type}} by 
									<a href="{{Config::get('app.url')}}/profile/{{$main->user->username}}"> 
										{{ $main->user->username }}
									</a>
								</div>
								<div class="excerpt">
									{{substr($main->body, 0,150)}}...
									<br/>
									<a class="read-more" href="{{Config::get('app.url')}}/posts/{{$main->alias}}">Read More</a>
									</a>
								</div>
							</div>
							
							<a class="image-link col-md-8" href="{{Config::get('app.url')}}/posts/{{$main->alias}}">
								<div class="image" style="background-image: url({{Config::get('app.imageurl')}}/{{$main->image}} )">
								</div>
								@if ( $main->nsfw )
									<div class="nsfw"></div>
								@endif
							</a>
							
							<?php unset($featured{0});?>
						@endif
					@endif
				
			</div>
		</div>
	</div>

	<div class="middle-wrapper">
		<div class="container middle-container">
			<div class="col-md-12">
				<h3 class="featured-label">- Featured Posts -</h3>
			</div>
			@foreach($featured as $k=>$f)
				
				@if($k == 3)


					<div class="col-md-4 col-sm-6 middle-grid promotional-container">
						<div class="promotional-box">
							<img class="new-cat" src="{{ URL::to('images/featured/new-cat-banner.png') }}" alt="New Category">
							<h3>First Time</h3>
							<ul class="new-cat-tags list-inline">
								<li>butterflies</li>
								<li>rave</li>
								<li>tattoo</li>
							</ul>
							<p class="subtext">Be one of the first to share your <span>“first time”</span> story.</p>

							@if( $is_guest )
								<a href="{{ URL::to( 'user/signup' ) }}" class="btn-flat-blue">Post your “First Time”</a>
							@else
								@if(Route::current()->uri() != 'myprofile/newpost')
									<a href="{{Config::get('app.url')}}/myprofile/newpost" class="btn-flat-blue">Post your “First Time”</a>
								@endif
							@endif

						</div>
					</div>

					{{--if this is the third item--}}
					{{--@if(Auth::check() && is_object($from_feed))
						{{--Somethign from the user's feed--}}
						{{--<div class="col-md-4 col-sm-6 middle-grid">
						@include('v2.partials.post-listing-partial', array('post'=> $from_feed))
						</div>
					@else
						{{--Signup box thing--}}
						{{--<div class="col-md-4 col-sm-6 middle-grid">
							<div class="post-container">
								<div class="signup-box">
									<div class="signup-top">
									</div>
									<div class="signup-content">
										<img class="join-community" src="{{ URL::to('images/featured/join-community.png') }}" alt="Join the Community">
										<div class="line"></div>
										<a href="{{ URL::to( 'user/signup' ) }}" class="btn-flat-blue" href="">Create An Account</a>
										<ul class="account-bullets">
											<li>post your own stories</li>
											<li>follow users</li>
											<li>save stories</li>
											<li>comment and join the discussion</li>
										</ul>
									</div>
									<div class="signup-bottom">
									</div>
								</div>
							</div>
						</div>
					@endif--}}
					<!--Feed Listing or Signup-->
					<div class="bar"></div>
				@endif
				
				{{--Under normal circumstances...--}}
				<div class="col-md-4 col-sm-6 middle-grid">
					@include('v2.partials.post-listing-partial', array('post'=> $f->post))
				</div>
					@if($k == 5)
						<?php break;?>
					@endif
			@endforeach
		</div>

		@if(!Auth::check())
		<div class="container signup-container">
			<div class="row">
				<div class="signup-bg">
					<div class="col-md-12 sign-up-row">
						<h3>Our stories live here.</h3>
						<p>Sign up to post your stories, follow, and comment.</p>
						<a href="{{ URL::to( 'user/signup' ) }}" class="btn-flat-blue">Create an account</a>
					</div>
				<div class="clearfix"></div>
				</div>
			</div>
		</div>
		@endif

		<div class="clearfix"></div>
	</div>
	
	

	@if( is_object($fuser) )
	@if(isset($fuser))
	<?php $recent = $fuser_recent ?>
	<div class="featured-user-container">	
			<h3 class="user-label">- Featured User -</h3>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-1 avatar-container">
					<a href="{{URL::to('profile/'.$fuser->user->username)}}">
						<?php $fuser_image = $fuser->user->image ? Config::get('app.imageurl').'/'.$fuser->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
						<span class="avatar" style="background-image:url({{$fuser_image}});"></span>
					</a>
				</div>
				<div class="col-md-6">
					<div class="col-md-12 user-info">
						<h4 class="user-name">
							<a href="{{URL::to('profile/'.$fuser->user->username)}}">
							{{$fuser->user->username}}
							</a>
						</h4>
						<div class="followers-container">
							<div class="featured-stats followers" id="followers">
								<span class="count">{{count($fuser->user->followers)}}</span>
								<span class="text">Followers</span>
							</div>

							<div class="featured-stats following" id="following">
								<span class="count">{{count($fuser->user->following)}}</span>
								<span class="text">Following</span>
							</div>
							<div class="featured-stats post-count">
								<span class="count">{{$post_count}}</span>
								<span class="text">Posts</span>
							</div>
						</div>
						<div class="quote">
							{{$fuser->excerpt}}
						</div>
						<div class="clearfix"></div>
					</div>
			
					<div class="recent-container col-md-12">
						<div class="row">
							<h5 class="recent-label col-md-12">Most Recent Post</h4>
							<?php $recent = $fuser_recent ?>
						</div>
						<div class="row">
							<div class="recent-post col-md-7 col-sm-6">
								<a href="{{URL::to( 'posts/'.$recent->alias)}}">
									<div class="recent-image" style="background-image:url('{{Config::get('app.imageurl')}}/{{$recent->image}}')">
									</div>
									<div class="recent-text">
										<p class="recent-title"> 
											{{$recent->title}}
										</p>
										<ul class="recent-taglines list-inline">
											<li> {{$recent->tagline_1}} </li>
											<li> {{$recent->tagline_2}} </li>
											<li> {{$recent->tagline_3}} </li>
										</ul>
									</div>
								</a>
							</div>
							<div class="user-actions col-md-5 col-sm-6">
								<a class="btn-outline-gold profile-action" href="{{URL::to('profile/'.$fuser->user->username)}}">
									View {{$fuser->user->username}}'s Profile
								</a>
								<a class="follow-button follow-action {{ $fuser_follow ? 'following' : '' }}" data-userid="{{$fuser->user_id}}" href="{{ URL::to('user/signup') }}">
									@if($fuser_follow)
										Following {{$fuser->user->username}}
									@else
										Follow {{$fuser->user->username}}
									@endif
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	@endif

	<div class="category-wrapper">
		<div class="footer-cat-wrapper">
			<div class="footer-cat-container container">
				<div class="footer-cat col-md-12">
					@include( 'v2/partials/category-listing' )
				</div>
			</div>
		</div>
	</div>
	
	
</div>
@stop

