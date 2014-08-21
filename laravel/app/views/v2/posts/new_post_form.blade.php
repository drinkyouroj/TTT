@extends('v2.layouts.master')

	@section('title')
	New Post | Two Thousand Times
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/new_post_form.css">
	@stop

	@section('js')
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/new-post.js"></script>
	@stop

	@section('content')

	<div class="controls-wrapper">

	</div>

	<div class="fixed">
	</div>

	{{--Wrapper is to be set as 100% and background black--}}
	<div class="top-submit-wrapper">
		{{--The big container so that we can assign the images to it. max-width 1440 or something like that--}}
		<div class="top-submit-container">

		</div>
	</div>

	@stop