@extends('v2.layouts.master')
	
	@section('title')
		My Profile | Two Thousand Times
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/myprofile/profile.css">
	@stop

	@section('js')
		
		<script>
			window.user_id = {{$profile_user->id}};
		</script>

		@include( 'v2/partials/post-listing-template' )
		@include( 'v2/myprofile/partials/profile-handlebars-template')

		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/myprofile/profile.js"></script>
	@stop

	@section('content')
		<div class="profile-header-wrapper">
			<div class="profile-header-container container">
				<div class="row">
					<div class="col-md-6 header-left">

					</div>
					<div class="col-md-6 header-right">
						<div class="row">
							<div class="col-md-6 settings">
								<a href="#settings" class="settings" id="settings">
									Settings
								</a>
							</div>
							<div class="col-md-6 follow">

								<a href="#followers" class="followers" id="followers">
									<span class="count">{{$follower_count}}</span>
									<span class="text">Followers</span>
								</a>

								<a href="#following" class="following" id="following">
									<span class="count">{{$following_count}}</span>
									<span class="text">Following</span>
								</a>
									
							</div>
						</div>
					</div>
					<div class="col-md-12 section-selectors">
						{{--Max and Anyone else, do not add any more classes to the below links--}}
						<a href="#collection" id="collection" class="active">
							Collection
						</a>

						@if($myprofile)
							<a href="#feed" id="feed">
								Feed
							</a>
							<a href="#saves" id="saves">
								Saves
							</a>
							<a href="#drafts" id="drafts">
								Drafts
							</a>
						@endif
						
					</div>
				</div>
			</div>
		</div>

		<div class="profile-content-wrapper">
			<div class="profile-content-container container">
				<div class="row" id="profile-content">

				</div>
			</div>
		</div>
	@stop