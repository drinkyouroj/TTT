@extends('layouts.master')

@section('js')

@stop

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/profile.css" rel="stylesheet" media="screen">
@stop


@section('filters')
	{{--Let's just include the Category filters by default--}}
	@include('partials/generic-filter')
@stop

{{--This is the general structure for most profile situations--}}
@section('content')


	@include('partials/profile-top')

@if(!isset($fullscreen) || !$fullscreen)
<div class="col-md-3">
	<div class="left-sidebar">
		@yield('left_sidebar')
		<div class="clearfix"></div>
	</div>
</div>
<div class="col-md-9">
	@yield('main')
	<div class="clearfix"></div>
</div>
@elseif(isset($fullscreen) && $fullscreen)
<div class="col-md-12">
	@yield('main')
	<div class="clearfix"></div>
</div>
@endif


@stop


