@extends('layouts.profile')


@section('title')
{{$user->username }}'s Posts
@stop

@section('main')
	<div class="row activity-nav">
		<a class="all">Show All</a>
		<a class="myposts">My Posts</a>
		<a class="myfavorites">My Favorites</a>
	</div>
	<div class="row activity-container generic-listing myposts">
		@if(count($myposts))
			{{--we're using part of the activity to render this so the foreach is set as $act--}}
			
			@if($featured)
				{? $act = $featured ?}
				{? $featured_item = 1 ?}
				@include('partials/activity-item')
			@endif
			
			{? $featured_item = 0 ?}
			@foreach($myposts as $act)
				@if(isset($act->post))
					@include('partials/activity-item')
				@endif
			@endforeach
		@endif
	</div>
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/myposts.js"></script>
		
		@include('partials/generic-handlebar-item')
@stop