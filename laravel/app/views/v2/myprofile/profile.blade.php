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
		window.featured_id = {{$profile_user->featured}};
		@if($myprofile)
			window.myprofile = true;

			@if(Session::get('post'))
				window.post = '{{Session::get('post')}}';
				<?php Session::put('post','')?>
			@else
				window.post = false;
			@endif

		@endif
	</script>

	@include( 'v2/partials/post-listing-template' )
	@include( 'v2/myprofile/partials/profile-handlebars-template' )
	@include( 'v2/myprofile/partials/no-content-handlebars-template' )

	@if($myprofile)
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/form/jquery.form.js"></script>		
	@endif
	
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/moment/moment.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/moment-timezone/moment-timezone-with-data.min.js"></script>

	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/myprofile/profile.js"></script>		
@stop

@section('content')
	<div class="profile-header-wrapper">
		<div class="profile-header-container container">
			<div class="row">
				<div class="col-md-6 header-left">
					<h2>
						<span class="avatar-image" style="background-image:url('{{Config::get('app.url')}}/uploads/avatars/{{$profile_user->username}}');"></span>
						{{$profile_user->username}}
					</h2>
				</div>
				<div class="col-md-6 header-right">
					<div class="row">
						<div class="col-md-5 settings">
							@if($myprofile)
							<a class="icon-button" href="#settings" id="settings">
								Settings
							</a>
							@endif
						</div>
						<div class="col-md-7 follow">

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
				<div class="col-md-8 section-selectors">
					<div class="border-box">
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
				@if(!$myprofile)
					<div class="col-md-4 follow-user">
						@if(!$is_following)
						<a class="follow follow-button">
							Follow
						</a>
						@else
						<a class="unfollow">
							UnFollow
						</a>
						@endif
					</div>
				@endif

				<div class="col-md-12 border">
					<div class="border-box"></div>
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

	
	@if($myprofile)
	{{--This is for the User Delete scenario--}}
		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
		      </div>
		      <div class="modal-body">
		        Are you absolutely sure???
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-danger delete-account" data-user="{{$profile_user->id}}">
		        	Delete The Account Already!
		        </button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	{{--Publish Modal--}}
	<div class="modal fade" id="publishModal" tabindex="-1" role="dialog" aria-labelledby="publishModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="publishModalLabel">
						Thanks for Posting.
					</h4>
				</div>
				<div class="modal-body">
					<div class="text">
						Thanks for Publishing your post.
						You can continue to add to change your post for up to 72hrs.<br/>
						Note: You can only make 1 post every 10 minutes.
					</div>
				</div><!--End of Modal Body-->
			</div>
		</div>
	</div>

	{{--Drafts Modal--}}
	<div class="modal fade" id="draftsModal" tabindex="-1" role="dialog" aria-labelledby="draftsModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="draftsModalLabel">
						Thanks for Posting.
					</h4>
				</div>
				<div class="modal-body">
					<div class="text">
						Thanks for writing a draft!
						You can continue to add to change your post for as long as you want.<br/>
						If you publish your post, you can continue to add to change your post for up to 72hrs.<br/>
						Note: You can only publish a post every 10 minutes.
					</div>
				</div><!--End of Modal Body-->
			</div>
		</div>
	</div>


@stop

@section('admin-mod-user-controls')
	<button>Do Stuff</button>
@stop
