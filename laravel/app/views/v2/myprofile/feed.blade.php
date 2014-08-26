@extends('v2.layouts.master')
	
	@section('title')
		Feed | Two Thousand Times
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/myprofile/feed.css">
	@stop

	@section('js')
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/feed.js"></script>
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
						<li><span class="filter filter-posts" data-feed-filter="posts">posts</span></li>
						<li><span class="filter filter-reposts" data-feed-filter="reposts">reposts</span></li>
					</ul>
				</div>
			</div>
		</section>

		<section class="posts-container feed-container">
			@if ( count( $feed ) )
				@foreach ( $feed as $item )
					@include( 'v2/myprofile/partials/profile-post-partial' )
				@endforeach
			@else
				<h2>Oops! No items were found in your feed...</h2>
			@endif
		</section>

	@stop