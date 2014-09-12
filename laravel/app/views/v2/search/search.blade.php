@extends('v2.layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/compiled/v2/search/search.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript">
		window.category = '{{Request::segment(2)}}';
		window.filter = '{{Request::segment(3)}}';
	</script>
	
	
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/generic-listing.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/search/search.js"></script>
	
	@include( 'v2/partials/post-listing-template' )
@stop

@if(isset($cat_title))
	{? $title =  $cat_title ?}
@else
	{? $title =  'Search' ?}
@endif


@section('title')
	{{$title}} | The Twothousand Times 
@stop

@section('content')
	
	@if ( isset($default))
	<h2>Default</h2>
	@else

		<div class="results-container search-results">
			<div class="results-header">
				<h2 class="search-term">Search for: <span>{{$term}}</span></h2>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#posts-results" data-toggle="tab">Posts</a></li>
					<li><a href="#users-results" data-toggle="tab">Users</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="tab-content">
					<div id="posts-results" class="posts-listing tab-pane active">
						<h3 class="search-type">Posts</h3>
						<div class="generic-listing">
							@if(!is_string($posts))
								
								@foreach($posts as $k => $post)
									@if(isset($post->id) && isset($post->user->username))
										@include( 'v2/partials/post-listing-partial' )
									@endif
								@endforeach
								
							@else
								<div class="col-md-12">
									No Posts Match the Search Term
								</div>
							@endif
						</div>
					</div>
					
					<div id ="users-results" class="users-listing tab-pane">
						<h3 class="search-type">Users</h3>
						<div class="generic-listing">
							@if(!is_string($users))
								<div class="row">
								@foreach($users as $k => $user)
									@if(isset($user->id))
										@include('partials/user-item')
									@endif
								@endforeach
								</div>
							@else
								<div class="col-md-12">
									No Users Match the Search Term
								</div>
							@endif
						</div>
					</div>
				</div>

			</div>
		</div>
	@endif
	
@stop