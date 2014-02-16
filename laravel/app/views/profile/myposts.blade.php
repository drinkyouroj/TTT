@extends('layouts.profile')


@section('title')
{{$user->username }}'s Profile

@stop

@section('main')

	<div class="row activity-container generic-listing myposts">
			
		@if(count($myposts))
			{{--we're using part of the activity to render this so the foreach is set as $act--}}
			@foreach($myposts as $act)
				@if($act->post->id != $user->featured)
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
@stop