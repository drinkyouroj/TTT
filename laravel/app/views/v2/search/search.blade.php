@extends('v2.layouts.master')

@section('css')
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/search/search.css" rel="stylesheet" media="screen">
@stop

@section('js')
	
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/views/generic-listing.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/search/search.js"></script>
	
	@include( 'v2/partials/post-listing-template' )
@stop

@section('title')
	Search | The Twothousand Times
@stop

@section('content')
	
	@if ( isset($default))
		<div class="search-container">
			<h2>Search <small>Find users and content</small></h2>
			<form class="search-form" action="{{Config::get('app.url')}}/search" method="get">
				<input type="text" name="search" placeholder="Search">
			</form>
		</div>


	@else
		<script type="text/javascript">
			window.search_term = '{{ $term }}';
			window.search_post_count = {{ count( $posts ) }};
			window.search_user_count = {{ count( $users ) }};
		</script>

		<div class="results-container search-results">
			<div class="results-header">
				<h2 class="search-term">Search results for: <span>{{$term}}</span></h2>
				<ul class="nav nav-tabs">
					<li class="<?php echo $filter == 'posts' ? 'active' : '' ?>"><a href="#posts-results" data-toggle="tab">Posts</a></li>
					<li class="<?php echo $filter == 'users' ? 'active' : '' ?>"><a href="#users-results" data-toggle="tab">Users</a></li>
					<li class="pull-right">
						<form class="search-form" action="{{Config::get('app.url')}}/search" method="get">
							<input type="text" name="search" placeholder="Search">
						</form>
					</li>
				</ul>
			</div>

			<div class="tab-content">
				<div id="posts-results" class="posts-listing tab-pane <?php echo $filter == 'posts' ? 'active' : '' ?>">
					<div class="generic-listing">
						@if( count( $posts ) )
							
							@foreach($posts as $k => $post)
								@if(isset($post->id) && isset($post->user->username))
									@include( 'v2/partials/post-listing-partial' )
								@endif
							@endforeach
							
						@else
							<div class="col-md-12 no-results">
								@if( $page == 1 )
									No posts match the search term: {{$term}}
								@else
									No more results were found for the search: {{$term}}
								@endif
							</div>
						@endif
					</div>
					<div class="pagination-container">
						
						@if ( $page > 1 )
							{{-- Display prev page button --}}
							<a class="btn btn-flat-gray" href="{{URL::to('search')}}?search={{$term}}&page={{$page - 1}}&filter=posts">&#8592; Prev Page</a>
						@endif
						@if ( $post_count == 12 )
							{{-- We have a full page of search results, display next page button --}}
							<a class="btn btn-flat-gray" href="{{URL::to('search')}}?search={{$term}}&page={{$page + 1}}&filter=posts">Next Page &#8594;</a>
						@else
							<a class="btn btn-flat-gray disabled" href="#">Next Page</a>
						@endif

					</div>
				<div class="clearfix"></div>
				</div>
				
				<div id ="users-results" class="users-listing tab-pane <?php echo $filter == 'users' ? 'active' : '' ?>">
					<div class="generic-listing">
						@if(count ($users))
							<div class="user-container">
							@foreach($users as $k => $user)
								@if(isset($user->id))
									@include('v2/partials/user-item')
								@endif
							@endforeach
							</div>
						@else
							<div class="col-md-12 no-results">
								@if( $users_page == 1 )
									No users match the search: {{$term}}
								@else
									No more users were found for the search: {{$term}}
								@endif
							</div>
						@endif
					</div>
				
					<div class="pagination-container">
						
						@if ( $users_page > 1 )
							{{-- Display prev page button --}}
							<a class="btn btn-flat-gray" href="{{URL::to('search')}}?search={{$term}}&page={{$users_page - 1}}&filter=users">&#8592; Prev Page</a>
						@endif
						@if ( $user_count == 12 )
							{{-- We have a full page of search results, display next page button --}}
							<a class="btn btn-flat-gray" href="{{URL::to('search')}}?search={{$term}}&page={{$users_page + 1}}&filter=users">Next Page &#8594;</a>
						@else
							<a class="btn btn-flat-gray disabled" href="#">Next Page</a>
						@endif

					</div>
				<div class="clearfix"></div>
				</div>

			</div>
		</div>
	@endif
	
@stop