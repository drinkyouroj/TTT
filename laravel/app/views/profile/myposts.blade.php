@extends('layouts.profile')


@section('title')
{{$user->username }}'s Profile

@stop

@section('main')

	<div class="row activity-container generic-listing">
			
		@if(count($myposts))
			{{--we're using part of the activity to render this so the foreach is set as $act--}}
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
@stop