@extends('layouts.profile')

@section('title')
Notifications | The Two Thousand Times
@stop

@section('main')
	{{-- 	@include('angular_views/notifcation') --}}
	<div class="row notifications-parent full-list">
		@if(count($notification_list))
			@foreach($notification_list as $not)
					@include('partials/notifications')
			@endforeach
		@endif
	</div>
@stop


@section('js')
	@parent
	
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/angular.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/app.js"></script>
	
@stop
