@extends('v2.layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/search.css" rel="stylesheet" media="screen">
@stop

@section('js')
	<script type="text/javascript">
		window.category = '{{Request::segment(2)}}';
		window.filter = '{{Request::segment(3)}}';
	</script>
	
	
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/generic-listing.js"></script>
	
	@include('partials/generic-handlebar-item')
@stop

@section('filters')
	@include('partials/generic-filter')
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
	<div class="container search-results">
		<h2 class="search-term">Search for: <span>{{$term}}</span></h2>
		<div class="row">
			<div class="col-md-9 posts-listing">
				<h3 class="search-type">Posts</h3>
				<div class="generic-listing">
					@if(!is_string($posts))
						<div class="row">
						@foreach($posts as $k => $post)
							@if(isset($post->id) && isset($post->user->username))
								@include('partials/generic-item')
							@endif
						@endforeach
						</div>
					@else
						<div class="col-md-12">
							No Posts Match the Search Term
						</div>
					@endif
				</div>
			</div>
			<div class="col-md-3 users-listing">
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

	
@stop