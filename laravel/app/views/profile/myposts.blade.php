@extends('layouts.profile')

@section('css')
	@parent
	<link href="{{Config::get('app.url')}}/css/views/myposts.css" rel="stylesheet" media="screen">
@stop

@section('title')
{{$user->username }}'s Posts
@stop

@section('main')
	<div class="row activity-nav">
		<li class="left"><a class="all active">Show All</a></li>
		<li><a class="myposts">My Posts</a></li>
		<li><a class="myfavorites">My Favorites</a></li>
	</div>
	<div class="row activity-container generic-listing myposts">
		@if(count($myposts))
			{{--we're using part of the activity to render this so the foreach is set as $act--}}
			
			@if($featured)
				{? $act = $featured ?}
				{? $featured_item = 1 ?}
				@include('partials/activity-item')
			@endif
			
			{? $featured_item = 0; ?}
			@foreach($myposts as $act)
			
				{{--Maybe in the future, we need to make the if statements part of the partial?--}}
				@if(is_string($act->post_type))
					@if($act->post->published)
						@include('partials/activity-item')
					@endif
				@endif
			@endforeach
		@endif
	</div>
@stop


@section('js')
	@parent
	
	<script>
		window.cur_user = '{{$user->username}}';
	</script>
	
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/myposts.js"></script>
		
		@include('partials/generic-handlebar-item')
@stop