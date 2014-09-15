@extends('v2.layouts.master')
	<?php
		if(Auth::check()) {
			$user = Auth::user();
			$is_mod = $user->hasRole('Moderator');
			$is_admin = $user->hasRole('Admin');
		} else {
			$is_admin = false;
			$is_mod = false;
		}
	?>

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post.css">
@stop

@section('js')
	@include( 'v2/posts/partials/comment-handlebars-template' )
	@include( 'v2/posts/partials/comment-reply-handlebars-template' )
	@include( 'v2/posts/partials/comment-edit-handlebars-template' )
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/moment/moment.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/moment-timezone/moment-timezone-with-data.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/comment-pagination.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/post_actions.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/post.js"></script>
@stop

@section('title')
	{{ $post->title }}
@stop

@section('content')

	<?php
		$save_tooltip = 'Save this to your profile';
		$save_term_active = 'Saved';
		$save_term = 'Save';

		$repost_tooltip = 'Share this with your followers';
		$repost_term_active = 'Reposted';
		$repost_term = 'Repost';

		$like_tooltip = 'Give this more visibility to the community';
		$like_term_active = 'Liked';
		$like_term = 'Like';

		$follow_term_active = 'Following';
		$follow_term = 'Follow';
	?>
	
	<section class="post-action-bar-wrapper">
		<div class="post-action-bar" data-post-id="{{ $post->id }}" data-user-id="{{ $post->user->id }}">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
						{{-- Only display like, repost, save if not the author --}}
						<?php
							$is_logged_in = Auth::check();
							$is_author = Auth::check() && $post->user->id == Auth::user()->id;
						?>
							@if( !$is_author )
								<div class="col-md-3 hidden hidden-sm hidden-xs utility-container">
									@if($favorited)
										<a data-action="read" class="read">Mark as Read</a>
									@endif
									<a data-action="flag" class="flag-button flag glyphicon glyphicon-flag">
										Flag</a>
								</div>

								<div class="col-md-3 col-xs-8 actions-container">
									<ul class="actions">
										<li class="like">
											<a data-action="like" class="like-button {{ $liked ? 'active' : '' }}" href="#" title="{{ $like_tooltip }}" data-toggle="tooltip" data-placement="bottom">
												<span class="{{ $liked ? 'hidden' : '' }}"> <span class="action-counts"> {{ $liked ? $post->likes->count() - 1 : $post->likes->count() }} </span> </span>
												<span class="{{ $liked ? '' : 'hidden' }}"> <span class="action-counts"> {{ $liked ? $post->likes->count() : $post->likes->count() + 1 }} </span> </span>
											</a>
										</li>

										<li class="repost">
											<a data-action="repost" class="repost-button {{ $reposted ? 'active' : '' }}" href="#" title="{{ $repost_tooltip }}" data-toggle="tooltip" data-placement="bottom">
												<span class="{{ $reposted ? 'hidden' : '' }}"> <span class="action-counts"> {{ $reposted ? $post->reposts->count() - 1 : $post->reposts->count() }} </span> </span>
												<span class="{{ $reposted ? '' : 'hidden' }}"> <span class="action-counts"> {{ $reposted ? $post->reposts->count() : $post->reposts->count() + 1 }} </span> </span>
											</a>
										</li>

										<li class="save">
											<a data-action="save" class="save-button {{ $favorited ? 'active' : '' }}" href="#" title="{{ $save_tooltip }}" data-toggle="tooltip" data-placement="bottom">
												<!-- <span class="{{ $favorited ? 'hidden' : ''}}"> <span class="action-counts"> {{ $favorited ? $post->favorites->count() - 1 : $post->favorites->count() }} </span> </span>
												<span class="{{ $favorited ? '' : 'hidden'}}"> <span class="action-counts"> {{ $favorited ? $post->favorites->count() : $post->favorites->count() + 1 }} </span> </span> -->
											</a>
										</li>
									<ul>
								</div>

								<div class="col-md-4 hidden-sm hidden-xs follow-container">
									<a data-action="follow" class="follow-button follow {{ $is_following ? 'active' : '' }}" href="#">
										<span class="{{ $is_following ? 'hidden' : '' }}"> {{ $follow_term }} {{ $post->user->username }} </span>
										<span class="{{ $is_following ? '' : 'hidden' }}"> {{ $follow_term_active }} {{ $post->user->username }} </span>
									</a>
								</div>
							@endif
							<div class="col-md-2 col-xs-4 comment-container">
								<a class="comment-button action-comment" href="#">Comment</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="post-heading-wrapper">
		<div class="post-heading-container container">
			<div class="row">
				<div class="post-heading col-md-4">
					<h2>{{ $post->title }}</h2>
					{{-- Admin edit capabilities --}}
					@if ( $is_admin )	
						<h2 class="hidden">
							<input class="admin-post-title form-control" type="text" value="{{$post->title}}">
						</h2>
					@endif
					<div class="line"></div>
					<ul class="post-taglines list-inline">
						<li> {{ $post->tagline_1 }} </li>
						<li> {{ $post->tagline_2 }} </li>
						<li> {{ $post->tagline_3 }} </li>
						{{-- Admin edit capabilities --}}
						@if ( $is_admin )	
							<li class="hidden"> <input class="admin-post-tagline-1 form-control" type="text" value="{{ $post->tagline_1 }}"> </li>
							<li class="hidden"> <input class="admin-post-tagline-2 form-control" type="text" value="{{ $post->tagline_2 }}"> </li>
							<li class="hidden"> <input class="admin-post-tagline-3 form-control" type="text" value="{{ $post->tagline_3 }}"> </li>
						@endif
					</ul>

					<div class="author">
						<a href="{{ URL::to('profile/'.$post->user->username ) }}">
							<img class="post-author-avatar" src="">
						</a>
						story by <a class="author-name" href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>
					</div>

					<ul class="post-categories list-inline">
						@for ($i = 0; $i < count($post->categories); $i++)
							<li> 
								<a href="{{ URL::to( 'categories/'.$post->categories[$i]->alias ) }}"> {{ strtoupper( $post->categories[$i]->title ) }} </a> 
								@if ( $i != count($post->categories) - 1 )
									/
								@endif
							</li>
						@endfor
					</ul>

				</div>
				<div class="post-image col-md-8" style="background-image: url('{{Config::get('app.imageurl')}}/{{$post->image}}');"></div>
			</div>
		</div>
	</section>

	<section class="post-content-wrapper">
		<div class="post-content-container container">
			@if( $post->published )
				<?php 
					$total = count( $bodyarray );
				?>
				@foreach( $bodyarray as $c => $body )
					<div class="row">
						<div class="col-md-10 col-md-offset-1 post-content-page-wrapper">
							<div class="post-content-page" id="{? echo $c ? '':'one' ?}">
								{{$body}}
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1 row-divider">
							<span class="page-count">{? echo $c+1 ?}/{{$total}}</span>
							<div class="clearfix"></div>
						</div>
					</div>
				@endforeach
			@else
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<h3 class="unpublished">This post has been unpublished.</h3>
					</div>
				</div>
			@endif
		</div>
		{{-- Admin edit capabilities --}}
		@if ( $is_admin )	
			<div class="post-content-container container hidden">
				<div class="row">
					<div class="col-md-10 col-md-offset-1 post-content-page-wrapper">
						<textarea class="admin-post-body form-control" rows="20" style="margin-top: 60px;">
							{{ $post->body }}
						</textarea>
					</div>
				</div>
			</div>
		@endif
	</section>

	<section class="post-comment-wrapper">
		<div class="post-comment-container container">
			<div class="row">
				<div class="comments col-md-10 col-md-offset-1">
					
					<form method="POST" accept-charset="UTF-8" class="form-horizontal comment-reply" role="form">
						<input name="post_id" type="hidden" value="{{ $post->id }}">
						<input name="reply_id" type="hidden" value="">
							<div class="form-group comment-form ">
							<label for="body" class="control-label">Comments ({{ count($post->comments) }})</label>
							<textarea class="form-control" required="required" minlength="5" name="body" cols="50" rows="10" id="body"></textarea>
							<span class="error"></span>
						</div>
										
						<div class="form-group pull-right">
							<input class="btn-flat-gray" type="submit" value="Comment">
						</div>
						
						<div class="clearfix"></div>
					</form>
					
					<div class="comments-listing">
						
					</div>
					<div class="comment-loading-container">
						<img src="{{ URL::to('images/posts/comment-loading.gif') }}">
					</div>
				</div>
			</div>
		</div>
	</section>
@stop


@section('admin-mod-post-controls')
	<?php 
		$featured = isset($featured) ? $featured : false; 
	?>
	<p class="post-title"> {{ $post->title }}
		<span class="post-featured-label label label-success {{ $featured ? '' : 'hidden' }}">
			@if ( $featured )
				Featured {{ $featured->position }}
			@endif
		</span>
	</p>
	<hr>
	{{-- Admin only access to featured controls --}}
	@if ( $is_admin )
		<div class="admin-post-featured-controls">
			{{ Form::select( 'admin-featured-position', Config::get('featured'), null, array( 'class' => 'form-control' ) ) }}
			<button class="admin-set-featured btn btn-xs btn-success">Set Featured Position</button>
			<button class="admin-unset-featured btn btn-xs btn-warning {{ $featured ? '' : 'hidden' }}">Remove from Featured</button>
			<br>
		</div>
		<hr>
	@endif

	<div class="mod-post-controls">
		@if ( $is_admin )
			<button class="admin-edit-post btn btn-xs btn-warning">Edit Post</button>
			<button class="admin-edit-post-submit btn btn-xs btn-success hidden">Submit Changes</button>
		@endif
		<button class="mod-post-delete btn btn-xs btn-danger {{ $post ? '' : 'hidden' }}">Delete This Post</button>
		<button class="mod-post-undelete btn btn-xs btn-default {{ $post ? 'hidden' : '' }}">Un-delete This Post</button>
		<hr>
		<p class="post-categories-title">Categories</p>
		<ul class="list-unstyled">
			@foreach ( $post->categories as $category )
				<li>
					{{ $category->title }}
					<button class="mod-post-category-delete btn btn-xs btn-warning" data-category-id="{{$category->id}}">Remove</button>
				</li>
			@endforeach
		</ul>
	</div>

	
@stop
