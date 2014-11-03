@extends('v2.layouts.master')
	
	@section('title')
		Feed | Sondry
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/myprofile/feed.css?v={{$version}}">
	@stop

	@section('js')
		@include( 'v2/partials/post-listing-template' )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/myprofile/feed.js?v={{$version}}"></script>
	@stop

	@section('content')

		<section class="container feed-header">
			<div class="row">
				<div class="col-md-6">
					<h1 class="feed-title">
						<img class="feed-my-avatar" src="">
						MY FEED
					</h1>
				</div>
				<div class="col-md-6 feed-filter">
					<ul class="list-inline pull-right">
						<li><span class="filter filter-all active" data-feed-filter="all">all</span></li>
						<li><span class="filter filter-posts" data-feed-filter="post">posts</span></li>
						<li><span class="filter filter-reposts" data-feed-filter="repost">reposts</span></li>
					</ul>
				</div>
			</div>
		</section>

		<section class="posts-container feed-container">
			@if ( count( $feed ) )
				@foreach ( $feed as $item )
					<?php
					 	$post = $item->post;
					 	$feed_type = $item->feed_type;
					 	$users = $item->users;
					?>
					@include( 'v2/partials/post-listing-partial' )
				@endforeach
			@else
				<h2>Oops! No items were found in your feed...</h2>
			@endif
		</section>

	@stop