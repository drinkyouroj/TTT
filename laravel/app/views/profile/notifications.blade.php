@extends('layouts.profile')

@section('main')

	<div class="row notifications-listing full-list">
		@if(count($notifications))
			{{--Below file has the foreach routine for both the top section and the full listing --}}
			{? $break = 100; $all = true; ?}
			@include('partials/notifications')
		@endif
	</div>

@stop