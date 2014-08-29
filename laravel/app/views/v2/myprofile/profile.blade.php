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

					</div>
					<div class="col-md-12 section-selectors">
						{{--Max and Anyone else, do not add any more classes to the below links--}}
						<a href="#collection" class="collection" id="active">
							Collection
						</a>

						@if($myprofile)
							<a href="#feed" class="feed">
								Feed
							</a>
							<a href="#saves" class="saves">
								Saves
							</a>
							<a href="#drafts" class="drafts">
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