@extends('v2.layouts.master')
	<?php
		$user = Auth::user();
	?>

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post.css">
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/posts/post.js"></script>
@stop

@section('title')
	{{ $post->title }}
@stop

@section('content')

	<section class="post-action-bar">
		{{-- Only display like, repost, save if not the author --}}
		<?php
			$is_logged_in = Auth::check();
			$is_author = Auth::check() && $post->user->id == Auth::user()->id;
		?>
		@if( $is_logged_in && !$is_author )
			<a href="#">Like</a>
			<a href="#">Repost</a>
			<a href="#">Save</a>
		@endif
		<a href="#">Comment</a>
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