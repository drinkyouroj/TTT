@extends('v2/layouts/master')

@section('js')

	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/featured/featured.js"></script>

	@include('v2.partials.post-listing-template')

	@if(Auth::check())
		<!--//user id is passed as a global variable-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script>
	@endif

	
@stop

@section('css')
	<link href="{{Config::get('app.url')}}/css/compiled/v2/featured/featured.css" rel="stylesheet" media="screen">
@stop

@section('title')
	The Two Thousand Times
@stop

@section('content')
<div class="featured-page">
	<div class="top-wrapper">
		<div classs="container">
			<div class="row">
				<div class="col-md-4">
					Category selector
				</div>
				<div class="col-md-4">
					<h1>The Two Thousand Times</h1>
				</div>
				<div class="col-md-4">
					date
				</div>
			</div>
		</div>
	</div>

	{{--We're caching the DB query right now for 10 minutes, but hopefully we'll get to caching the view--}}
	@include('v2.featured.featured-cached')

	<div class="trending-wrapper">
		<div class="container">
			<div class="row">
				<h2>Trending</h2>
				<div class="col-md-12 trending-content">

				</div>
			</div>
		</div>
	</div>

</div>
@stop

