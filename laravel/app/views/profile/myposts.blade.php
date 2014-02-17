@extends('layouts.profile')


@section('title')
{{$user->username }}'s Profile

@stop

@section('main')
	<div class="row activity-nav">
		<a class="all">Show All of My Posts</a>
		<a class="myposts">Show My Posts</a>
		<a class="myfavorites">Show My Favorites</a>
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
				@include('partials/activity-item')
			@endforeach
		@endif
	</div>
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/myposts.js"></script>
@stop