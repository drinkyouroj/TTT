@extends('v2/layouts/master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('title')
	The Two Thousand Times
@stop

@section('content')
	{{--We'll implement the layout selector in the backend and we should use partials at that point to separate the layouts.--}}
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="top-featured" class="top-featured generic-listing">
					<div class="gutter-sizer"></div>
					{{--This is temporary to get things working.--}}
					@foreach($featured as $k=>$featured)
						{? $post = $featured->post?}
						@include('partials.featured-item')
					@endforeach
				</div>
			</div>
		</div>
	</div>
@stop


@section('js')
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/packery.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/home.js"></script>	
	@if(Auth::check())
		<!--//user id is passed as a global variable-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script>
	@else
		
	@endif
	
	@include('partials/featured-handlebar-item')
	
@stop


@section('css')
	<link href="{{Config::get('app.url')}}/css/views/featured.css" rel="stylesheet" media="screen">
@stop