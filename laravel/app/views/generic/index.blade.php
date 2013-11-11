@extends('layouts.master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	
	@if(Auth::check())
			{{--Add anything that's required by a logged in user.--}}
	
	@endif
	
		
	@foreach($posts as $post)
		<p>Test {{$post->id}}</p>
	@endforeach

@stop
