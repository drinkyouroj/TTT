@extends('layouts.master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	
	@if(Auth::check())
			{{--Add anything that's required by a logged in user.--}}
	
	@endif
	<h2>{{$post->title}}</h2>
	<div class="the-content">
		{{$post->body}}
	</div>
	

@stop
