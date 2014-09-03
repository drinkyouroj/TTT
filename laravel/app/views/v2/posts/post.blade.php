@extends('v2.layouts.master')
	<?php
		$user = Auth::user();
	?>

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post.css">
@stop

@section('js')
	@include( 'v2/posts/partials/comment-handlebars-template')
	@include( 'v2/posts/partials/comment-reply-handlebars-template')
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
			{{-- Only display like, repost, save if not the author --}}
			<?php
				$is_logged_in = Auth::check();
				$is_author = Auth::check() && $post->user->id == Auth::user()->id;
			?>
			@if( !$is_author )
				<a data-action="follow" class="follow-button follow" href="#">
					<span class="{{ $is_following ? 'hidden' : '' }}"> {{ $follow_term }} {{ $post->user->username }} </span>
					<span class="{{ $is_following ? '' : 'hidden' }}"> {{ $follow_term_active }} {{ $post->user->username }} </span>
				</a>
				<ul class="actions">
					<li class="like">
						<a data-action="like" class="like-button" href="#" title="{{ $like_tooltip }}" data-toggle="tooltip" data-placement="bottom">
							<span class="{{ $liked ? 'hidden' : '' }}">  {{ $like_term }} <span class="action-counts"> {{ $liked ? $post->likes->count() - 1 : $post->likes->count() }} </span> </span>
						</a>
						<span class="{{ $liked ? '' : 'hidden' }}">  {{ $like_term_active }} <span class="action-counts"> {{ $liked ? $post->likes->count() : $post->likes->count() + 1 }} </span> </span>
					</li>

					<li class="repost">
						<a data-action="repost" class="repost-button" href="#" title="{{ $repost_tooltip }}" data-toggle="tooltip" data-placement="bottom">
							<span class="{{ $reposted ? 'hidden' : '' }}"> {{ $repost_term }} <span class="action-counts"> {{ $reposted ? $post->reposts->count() - 1 : $post->reposts->count() }} </span> </span>
						</a>
						<span class="{{ $reposted ? '' : 'hidden' }}"> {{ $repost_term_active }} <span class="action-counts"> {{ $reposted ? $post->reposts->count() : $post->reposts->count() + 1 }} </span> </span>
					</li>

					<li class="save">
						<a data-action="save" class="save-button" href="#" title="{{ $save_tooltip }}" data-toggle="tooltip" data-placement="bottom">
							<span class="{{ $favorited ? 'hidden' : ''}}"> {{ $save_term }} <span class="action-counts"> {{ $favorited ? $post->favorites->count() - 1 : $post->favorites->count() }} </span> </span>
						</a>
						<span class="{{ $favorited ? '' : 'hidden'}}"> {{ $save_term_active }} <span class="action-counts"> {{ $favorited ? $post->favorites->count() : $post->favorites->count() + 1 }} </span> </span>
					</li>
				<ul>
			@endif

			<a class="action-comment btn-flat-blue" href="#">Comment</a>

			@if ( $is_author && $is_editable )
				<a href="{{ URL::to( 'profile/editpost/'.$post->id ) }}">Edit Post</a>
			@endif
		</div>
	</section>

	<section class="post-heading-wrapper">
		<div class="post-heading-container container">
			<div class="row">
				<div class="post-heading col-md-4">

					<h2>{{ $post->title }}</h2>
					<div class="line"></div>
					<ul class="post-taglines list-inline">
						<li> {{ $post->tagline_1 }} </li>
						<li> {{ $post->tagline_2 }} </li>
						<li> {{ $post->tagline_3 }} </li>
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
								{{ $body }}
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
	</section>

	<section class="post-comment-wrapper">
		<div class="post-comment-container container">
			<div class="row">
				<div class="comments col-md-10 col-md-offset-1">
					
					<form method="POST" accept-charset="UTF-8" class="form-horizontal comment-reply" role="form">
						<input name="post_id" type="hidden" value="{{ $post->id }}">
						<input name="reply_id" type="hidden" value="">
							<div class="form-group comment-form ">
							<label for="body" class="control-label">Comments ({{ $post->comments->count() }})</label>
							<textarea class="form-control" required="required" minlength="5" name="body" cols="50" rows="10" id="body"></textarea>
							<span class="error"></span>
						</div>
										
						<div class="form-group pull-right">
							<input class="btn-flat-dark-gray" type="submit" value="Comment">
						</div>
						
						<div class="clearfix"></div>
					</form>
					
					<div class="comments-listing">
						
					</div>
				</div>
			</div>
		</div>
	</section>
@stop