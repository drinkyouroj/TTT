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
		<div class="container">
			<div class="row">
				<div class="col-md-12 heading-title">
					<h1>Two Thousand Times</h1>
					<div class="date-circle">
						<span class="month">
							December
						</span>
						<span class="day">
							30
						</span>
					</div>
				</div>
				<div class="col-md-12 cat-selector">
					<section class="filters">
						<div class="category-filter-container">
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
					</section>
				</div>
			</div>
		</div>
	</div>

	{{--We're caching the DB query right now for 10 minutes, but hopefully we'll get to caching the view--}}
	@include('v2.featured.featured-cached')

	<div class="trending-wrapper">
		<h2>Trending</h2>
		<div class="trending-content">

		</div>
	</div>

</div>
@stop

