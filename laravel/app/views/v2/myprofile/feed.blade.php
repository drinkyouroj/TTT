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

		<section class="container">
			<div class="row">
				<div class="col-md-6">
					<h1 class="feed-title">
						<img class="feed-my-avatar" src="">
						MY FEED
					</h1>
				</div>
				<div class="col-md-6">
					<ul class="list-inline pull-right">
						<li>all</li>
						<li>posts</li>
						<li>reposts</li>
					</ul>
				</div>
			</div>
		</section>

		<section class="isotope-container">
			@if ( count( $feed ) )
				@foreach ( $feed as $item )
					@include( 'partials/profile.post.partial' )
				@endforeach
			@else
				<h2>Oops! No items were found in your feed...</h2>
			@endif
		</section>
		

	@stop