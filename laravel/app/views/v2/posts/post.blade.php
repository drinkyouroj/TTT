@extends('v2.layouts.master')
	<?php
		$user = Auth::user();
	?>

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post.css">
@stop

@section('js')
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
		<div class="post-action-bar system-share" data-post-id="{{ $post->id }}" data-user-id="{{ $post->user->id }}">
			{{-- Only display like, repost, save if not the author --}}
			<?php
				$is_logged_in = Auth::check();
				$is_author = Auth::check() && $post->user->id == Auth::user()->id;
			?>
			@if( !$is_author )
				<a href="#" data-action="follow">
					<span class="{{ $is_following ? 'hidden' : '' }}"> {{ $follow_term }} {{ $post->user->username }} </span>
					<span class="{{ $is_following ? '' : 'hidden' }}"> {{ $follow_term_active }} {{ $post->user->username }} </span>
				</a>
				
				<a href="#" title="{{ $like_tooltip }}" data-action="like" data-toggle="tooltip" data-placement="bottom">
					<span class="{{ $liked ? 'hidden' : '' }}">  {{ $like_term }} <span class="action-counts"> {{ $liked ? $post->likes->count() - 1 : $post->likes->count() }} </span> </span>
					<span class="{{ $liked ? '' : 'hidden' }}">  {{ $like_term_active }} <span class="action-counts"> {{ $liked ? $post->likes->count() : $post->likes->count() + 1 }} </span> </span>
				</a>

				<a href="#" title="{{ $repost_tooltip }}" data-action="repost" data-toggle="tooltip" data-placement="bottom">
					<span class="{{ $reposted ? 'hidden' : '' }}"> {{ $repost_term }} <span class="action-counts"> {{ $reposted ? $post->reposts->count() - 1 : $post->reposts->count() }} </span> </span>
					<span class="{{ $reposted ? '' : 'hidden' }}"> {{ $repost_term_active }} <span class="action-counts"> {{ $reposted ? $post->reposts->count() : $post->reposts->count() + 1 }} </span> </span>
				</a>

				<a href="#" title="{{ $save_tooltip }}" data-action="save" data-toggle="tooltip" data-placement="bottom">
					<span class="{{ $favorited ? 'hidden' : ''}}"> {{ $save_term }} <span class="action-counts"> {{ $favorited ? $post->favorites->count() - 1 : $post->favorites->count() }} </span> </span>
					<span class="{{ $favorited ? '' : 'hidden'}}"> {{ $save_term_active }} <span class="action-counts"> {{ $favorited ? $post->favorites->count() : $post->favorites->count() + 1 }} </span> </span>
				</a>

			@endif

			<a class="action-comment" href="#">Comment</a>
			@if ( $is_author && $is_editable )
				<a href="{{ URL::to( 'profile/editpost/'.$post->id ) }}">Edit Post</a>
			@endif
		</div>
	</section>

	<section class="post-heading-container container">
		<div class="row">
			<div class="post-heading col-md-12">
				<h2>{{ $post->title }}</h2>
				<ul class="post-taglines list-inline">
					<li> {{ $post->tagline_1 }} </li>
					<li> {{ $post->tagline_2 }} </li>
					<li> {{ $post->tagline_3 }} </li>
				</ul>
				<a href="{{ URL::to('profile/'.$post->user->username ) }}">
					<img class="post-author-avatar" src="">
				</a>
				story by <a href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>

			</div>
		</div>
	</section>

	<section class="post-image-container container">
		<div class="row">
			<div class="post-image col-md-12" style="background-image: url('{{Config::get('app.imageurl')}}/{{$post->image}}');"></div>	
		</div>
	</section>

	<section class="post-content container">
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
	</section>

	<section class="post-comment-container container">
		<div class="row">
			@if( count( $post->comments ) )
				<div class="comments col-md-10 col-md-offset-1">
					@if( Auth::check() )
						@include('generic.commentform', array('post' => $post) )
					@endif
					
					<ul class="comments-listing">
						@if( count( $post->nochildcomments) )
							{{ View::make('generic/comment')->with('comments', $post->nochildcomments) }}
						@endif
					</ul>
				</div>
			@else
				<div class="comments col-md-10 col-md-offset-1 no-comments">
					@if( Auth::check() )
						@include('generic.commentform', array('post' => $post) )
					@endif
					
					<p>No Comments have been made yet...</p>
				</div>
			@endif
		</div>
	</section>
@stop