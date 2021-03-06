@extends('v2.layouts.master')
	
@section('title')
	{{$profile_user->username}}'s Profile | Sondry
@stop

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/myprofile/profile.css?v={{$version}}">
@stop

@section('js')
	
	<script>
		window.user_id = {{$profile_user->id}};
		window.featured_id = {{$profile_user->featured}};
		window.user_image = '{{$profile_user->image}}';
		@if($myprofile)
			window.myprofile = true;

			@if(Session::get('post'))
				window.post = '{{Session::get('post')}}';
				<?php Session::put('post','')?>
			@else
				window.post = false;
			@endif

			@if(strlen($profile_user->email))
				window.email = 1;
			@else
				window.email = 0;
			@endif

		@endif
	</script>

	{{--Handlebars--}}
	@include( 'v2/partials/post-listing-template' )
	
	{{--Profile Stuff--}}
	@include( 'v2/myprofile/partials/profile-handlebars-template' )
	@include( 'v2/myprofile/partials/notifications-handlebars-template' )
	@include( 'v2/myprofile/partials/settings-handlebars-template' )

	@include( 'v2/myprofile/partials/no-content-handlebars-template' )
	@include( 'v2/partials/photo-input' )

	@if( $myprofile || $is_admin )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/form/jquery.form.js"></script>
	@endif
	@if( $is_admin )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/myprofile/profile-admin.js"></script>
	@endif

	{{--This is for the follow action--}}
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post_actions.js?v={{$version}}"></script>
	

	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>	
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/moment/moment.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/moment-timezone/moment-timezone-with-data.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/photo/photo.js?v={{$version}}"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/myprofile/profile.js?v={{$version}}"></script>

	<!--Signup success {{Session::get('signup_success')}}-->
	@if(Session::get('signup_success',false))
		<?php Session::forget('signup_success');?>
		<script type="text/javascript">
			ga('send', 'event', 'signup','success','{{$profile_user->username}}');
		</script>
	@endif
	
@stop

@section('content')
	<div class="profile-header-wrapper">
		<div class="profile-header-container container">
			<div class="row">
				<div class="col-sm-8 col-xs-6 header-left">
					<div class="avatar-container">
						@if($profile_user->image)
							<span class="avatar-image" data-toggle="modal" data-target="<?php echo $myprofile ? '#photoModal': '#avatarModal';?>" style="background-image:url('{{Config::get('app.imageurl')}}/{{$profile_user->image}}');"></span>
						@else
							<span class="avatar-image" data-toggle="modal" data-target="<?php echo $myprofile ? '#photoModal': '#avatarModal';?>" style="background-image:url('{{Config::get('app.url')}}/images/profile/avatar-default.png');"></span>
						@endif
					</div>
					<div class="name-info">
						<h2>
							<a class="username">	
								{{$profile_user->username}}
							</a>
						</h2>

						<div class="info">
							@if($profile_user->name)
								<span class="profile-name info-generic">
									{{$profile_user->name}}
								</span>
							@endif
							
							@if($profile_user->website)
								<span class="profile-website info-generic">
									
									<a rel="nofollow" href="{{$profile_user->website}}" target="_blank">
										{{preg_replace('#^https?://#', '', $profile_user->website)}}
									</a>
								</span>
							@endif
						<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xs-6 header-right">
					<div class="row">
						<div class="col-md-12 follow-container">
							<div class="col-md-12 col-sm-12 col-xs-12 fing-fer">
								<a href="#followers" class="followers" id="followers">
									<span class="count">{{$follower_count}}</span>
									<span class="text">Followers</span>
								</a>

								<a href="#following" class="following" id="following">
									<span class="count">{{$following_count}}</span>
									<span class="text">Following</span>
								</a>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 follow-btn-container">
								@if($myprofile)
									<div class="settings">
										<a class="icon-button" href="#settings" id="settings">
											Settings
										</a>
									</div>
								@else
									@if(!$is_following)
									<a class="follow follow-button">
										Follow
									</a>
									@else
									<a class="follow following-button">
										Following
									</a>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-xs-12 section-selectors">
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
							<a href="#notifications" id="notifications">
								Notifications
							</a>
						@endif
					</div>
				</div>
				<div class="col-md-4 col-xs-12 follow-user">
				
				</div>
				<div class="col-md-12 col-xs-12 border">
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
	{{--Contains modals for My Profile --}}


	{{--This is for the User Delete scenario--}}
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Account deactivation</h4>
	      </div>
	      <div class="modal-body">
	        Are you absolutely sure?
	      </div>
	      <div class="modal-footer">

	        <div class="input-group">
		      <input type="password" placeholder="Password" class="form-control delete-account-password">
		      <span class="input-group-btn">
		      	<button type="button" class="btn btn-danger delete-account" data-user="{{$profile_user->id}}">
		        	Deactivate Already!
		        </button>
		      	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		      </span>
		    </div>

		    <div class="delete-account-error col-md-12">

		    </div>
	        
	      </div>
	    </div>
	  </div>
	</div>
	

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
						Success! You have up to 72 hours to edit your post. Posts can be unpublished and republished at any time. You’ll be able to post again in a matter of minutes. 
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
					</h4>
				</div>
				<div class="modal-body">
					<div class="text">
						Your unfinished post has been moved to the Drafts section in your profile. You can update and publish it at any time. 
					</div>
				</div><!--End of Modal Body-->
			</div>
		</div>
	</div>

	{{--Photo Modal--}}
	<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="photoModalLabel">
						Choose an Avatar
					</h4>
				</div>
				<div class="modal-body">
					
				</div><!--End of Modal Body-->
				<div class="modal-footer hidden">
					<button class="btn-flat-blue pull-right accept-photo" data-dismiss="modal">Ok</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="removeDraftModal" tabindex="-1" role="dialog" aria-labelledby="removeDraftModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="removeDraftModalModalLabel">
						Are you sure you want to remove this draft?
					</h4>
				</div>
				<div class="modal-body">
					<div class="input-group col-md-8 col-md-offset-2">
				      <span class="input-group-btn">
				        <button type="button" class="btn btn-danger delete" >
				        	Remove The Draft.
				        </button>
				      </span>
				    </div>
				</div><!--End of Modal Body-->
				<div class="modal-footer hidden">
					<button class="btn-flat-blue pull-right" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
@else
	{{-- If not your profile, provide modal when clicking user's avatar --}}
	<div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						{{$profile_user->username}}
					</h4>
				</div>
				<div class="modal-body">
					@if($profile_user->image)
						<span class="avatar-image" style="background-image:url('{{Config::get('app.imageurl')}}/{{$profile_user->image}}');"></span>
					@else
						<span class="avatar-image" style="background-image:url('{{Config::get('app.url')}}/images/profile/avatar-default.png');"></span>
					@endif
				</div><!--End of Modal Body-->
				<div class="modal-footer">
					<button class="btn-flat-blue pull-right" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
@endif

@stop


<?php

	if(Auth::check()) {
		$user = Auth::user();
	}

	$profile_user_is_mod = $profile_user->hasRole('Moderator');
	$profile_user_is_admin = $profile_user->hasRole('Admin');
	$profile_user_banned = $profile_user->banned;
	$profile_user_is_deleted = $profile_user->deleted_at != null;
?>

@section('admin-mod-user-controls')
	<div class="mod-user-controls">
	@if ( $profile_user_is_admin )
		<p>This user is an admin</p>
	@else
		<p class="user-name">Current user: {{$profile_user->username}}</p>

		<button class="mod-ban-user btn btn-xs btn-warning {{ $profile_user_banned ? 'hidden' : '' }}">Ban</button>
		<button class="mod-unban-user btn btn-xs btn-default {{ $profile_user_banned ? '' : 'hidden' }}">Un-Ban</button>
		<br>
		@if ( $is_admin )
			<button class="admin-assign-moderator btn btn-xs btn-warning {{ $profile_user_is_mod ? 'hidden' : '' }}">Assign as Moderator</button>
			<button class="admin-unassign-moderator btn btn-xs btn-default {{ $profile_user_is_mod ? '' : 'hidden' }}">Revoke Moderator Status</button>
			<br>
			<button class="admin-soft-delete btn btn-xs btn-warning {{ $profile_user_is_deleted ? 'hidden' : '' }}">Soft Delete</button>
			<button class="admin-restore-soft-delete btn btn-xs btn-default {{ $profile_user_is_deleted ? '' : 'hidden' }}">Restore Soft Deleted User</button>
			<br>
			<button class="admin-user-reset btn btn-xs btn-warning">Reset User Password</button>
			<br>

			<hr>
			
			<form role="form" class="form-horizontal" id="featureUser" method="post" action="{{ URL::to('admin/feature/user') }}">
				Feature this user
				<input type="hidden" name="user_id" value="{{$profile_user->id}}">
				<textarea name="excerpt" value="" placeholder="Excerpt"></textarea>
				<button class="btn btn-default btn-flat-dark-gray">Set Featured</button>
			</form>

			<hr>

			{{-- Show the user's email preferences --}}
			<p>{{$profile_user->username}}'s Email Preferences</p>
			@if ( $email_prefs == false )
				<p><span class="label label-danger">Email Preferences have not been set!</span></p>
			@else
				<p>Views:
					@if( $email_prefs->views )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
				<p>Comments:
					@if( $email_prefs->comment )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
				<p>Replies:
					@if( $email_prefs->reply )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
				<p>Likes:
					@if( $email_prefs->like )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
				<p>Follows:
					@if( $email_prefs->follow )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
				<p>Reposts:
					@if( $email_prefs->repost )
						<span class="label label-success">Yes</span> 
					@else
						<span class="label label-danger">No</span> 
					@endif
				</p>
			@endif
			

			@if ( count( $deleted_posts ) )
				<hr>
				<p>{{$profile_user->username}}'s Deleted Posts</p>
				<ul class="list-unstyled">
					@foreach( $deleted_posts as $post )
						<li>
							<a href="{{ URL::to('posts/'.$post->alias) }}">{{ $post->title }}</a>
						</li>
					@endforeach
				</ul>
			@endif			
			

		@endif
	@endif
	</div>
@stop
