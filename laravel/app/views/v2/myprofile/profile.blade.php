@extends('v2.layouts.master')
	
	@section('title')
		Feed | Two Thousand Times
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/myprofile/feed.css">
	@stop

	@section('js')
		
		@include( 'v2/partials/post-listing-template' )

		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/myprofile/profile.js"></script>
	@stop

	@section('content')
	@stop