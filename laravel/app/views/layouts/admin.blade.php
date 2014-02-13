@extends('layouts.master')

@section('js')

@stop

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/admin.css" rel="stylesheet" media="screen">
@stop

@section('content')
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
@stop