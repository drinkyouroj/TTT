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
		$is_guest = Auth::guest();
	?>
	
	@section('title')
		{{$cat_title}} | Sondry
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/category/category.css?v={{$version}}">
	@stop

	@section('js')
		@include( 'v2/partials/post-listing-template' )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/category/category.js?v={{$version}}"></script>
	@stop

	@section('content')

		<div class="promotional-wrapper" >
			<div class="container">
				<div class="row">
					<div class="promotional-container">
						<div class="col-md-2 col-sm-4 col-xs-4 image-container">
							<img class="new-cat" src="{{ URL::to('images/featured/new-cat-banner.png') }}" alt="New Category">
						</div>
						<div class="col-md-3 col-sm-4 col-xs-8">
							<h3>First Time</h3>
						</div>
						<div class="col-md-5 hidden-sm col-xs-8">
							<p class="subtext">Be one of the first to share your <span>“first time”</span> story.</p>
						</div>
						<div class="col-md-2 col-xs-4">
							@if( $is_guest )
								<a href="{{ URL::to( 'user/signup' ) }}" class="btn-flat-blue">Post Now</a>
							@else
								@if(Route::current()->uri() != 'myprofile/newpost')
									<a href="{{Config::get('app.url')}}/myprofile/newpost" class="btn-flat-blue">Post Now</a>
								@endif
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>

		<section class="category-header">
			<h1 class="category-title">
				{{ $cat_title }}
			</h1>
			@if(!$cat_desc)
				<p class="category-description"></p>
			@else
				<p class="category-description" data-category-alias="{{$cat_alias}}">{{$cat_desc}}</p>
			@endif
		</section>

		<section class="filters" data-current-category="{{$current_category}}" data-current-filter="{{$current_filter}}">
			<div class="filters-container">
				<div class="category-filter-container cat-container">
					<div class="category-filter-title">
						<span class="category-title">
							{{ $cat_title }}
						</span>
					</div>
					<ul class="filter-dropdown list-unstyled">
						{{-- I dont display the active category in the dropdown list --}}
						
						@if($current_category != 'all')
							<li><a href="{{ URL::to('categories/all') }}" class="filter filter-category" data-category-filter="all">All</a><li>
						@endif

						@if($current_category != 'new')
							<li><a href="{{ URL::to('categories/new') }}" class="filter filter-category" data-category-filter="new">New</a><li>
						@endif						

						@foreach ( $categories as $category )
							<?php
								$is_active = $category->alias == $current_category;
							?>
							@if ( !$is_active )
								<li><a href="{{ URL::to('categories/'.$category->alias) }}" class="filter filter-category" data-category-filter="{{$category->alias}}">{{$category->title}}</a></li>
							@endif
						@endforeach
					</ul>
				</div>

				@if($current_category != 'new')
				<div class="category-filter-container sort-container">
					<div class="category-filter-title">
						@foreach ( $filters as $filter => $filter_title )
							@if ( $filter == $current_filter )
								<span class="sortby-title"> {{ $filter_title }} </span>
							@endif
						@endforeach
					</div>
					<ul class="filter-dropdown list-unstyled">
						@foreach ( $filters as $filter => $filter_title )
							<?php 
								$is_active = $filter == $current_filter; 
							?>
							@if ( !$is_active )
								<li><a href="{{ URL::to('categories/'.$current_category.'/'.$filter) }}" class="filter filter-sortby" data-sortby-filter="{{$filter}}">{{$filter_title}}</a></li>
							@endif
						@endforeach
					</ul>
				</div>
				@endif
			</div>
		<div class="clearfix"></div>
		</section>
		

		<section class="posts-container feed-container">
			@if(count($posts))
				@foreach ( $posts as $post )
					@include( 'v2/partials/post-listing-partial' )
				@endforeach
			@else
				<h2>Oops! No posts were found in this category...</h2>
			@endif
		</section>

		<div class="loading-container">
			<img src="{{ URL::to('images/posts/comment-loading.gif') }}">
		</div>

	@stop

	@if ( $cat_desc )
		@section('admin-mod-category-controls')
			<button class="btn admin-edit-category-description">Edit Category Description</button>
			<button class="btn btn-success admin-edit-category-description-submit hidden">Submit Changes</button>
		@stop
	@endif