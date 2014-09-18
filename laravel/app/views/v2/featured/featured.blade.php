@extends('v2/layouts/master')

@section('js')

	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/featured/featured.js"></script>

	@include('v2.partials.post-listing-template')

	@if(Auth::check())
		<!--//user id is passed as a global variable-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script>
	@endif

	
@stop

@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/featured/featured.css" rel="stylesheet" media="screen">

	{{--CSS and heading is the same so its not an issue to put that stuff here.--}}
	<meta property="og:title" content="Two Thousand Times" />
	<meta property="og:description" content="Every life is a story waiting to be heard." />
	<meta property="og:image" content="{{ Config::get('app.url').'/images/profile/avatar-default.png' }}" />
	<meta property="og:type" content="article" />
@stop

@section('title')
	The Two Thousand Times
@stop

@section('content')
<div class="featured-page">
	<div class="top-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12 heading-title">
					<h1>Two Thousand Times</h1>
				</div>
				<div class="col-md-12 date">
					<div class="date-circle">
						<span class="month">
							{{date('F')}}
						</span>
						<span class="day">
							{{date('j')}}
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{--We're caching the DB query right now for 10 minutes, but hopefully we'll get to caching the view--}}
	@include('v2.featured.featured-cached')

	<div class="trending-wrapper">
		<h2>Trending</h2>
		<section class="filters">
			<div class="filters-container category-filter-container">
				<div class="category-filter-title cat-container">
					Categories
				</div>
				<ul class="filter-dropdown list-unstyled">
					{{-- I dont display the active category in the dropdown list --}}
					@foreach ( $categories as $category )
						<li><a href="{{ URL::to('categories/'.$category->alias) }}" class="filter filter-category" data-category-filter="{{$category->alias}}">{{$category->title}}</a></li>
					@endforeach
				</ul>
			</div>
		<div class="clearfix"></div>
		</section>
		<div class="trending-content">

		</div>
		<div class="clearfix"></div>
	</div>

	<div class="loading-container">
		<img src="{{ URL::to('images/posts/comment-loading.gif') }}">
	</div>
</div>
@stop

