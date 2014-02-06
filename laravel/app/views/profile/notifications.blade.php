@extends('layouts.profile')

@section('main')

	<div class="row notifications-listing">
		@if(count($notifications))
			{{--Below file has the foreach routine for both the top section and the full listing --}}
			@include('partials/notifications')
		@endif
	</div>

@stop