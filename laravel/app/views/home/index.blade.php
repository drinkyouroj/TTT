@extends('layouts/master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	{{--We'll implement the layout selector in the backend and we should use partials at that point to separate the layouts.--}}
	<div class="col-md-12">
		<div id="top-featured" class="top-featured generic-listing">
			{{--This is temporary to get things working.--}}
			@foreach($featured as $k=>$post)
				@include('partials.featured-item')
			@endforeach
		</div>
	</div>
@stop


@section('js')
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/packery.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/home.js"></script>	
	@if(Auth::check())
		<!--//user id is passed as a global variable-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script>
	@else
		
	@endif
@stop


@section('css')
	<link href="{{Config::get('app.url')}}/css/views/featured.css" rel="stylesheet" media="screen">
@stop