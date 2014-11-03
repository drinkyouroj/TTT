@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('title')
	About | Two Thousand Times
@stop

@section('content')
<div class="about-wrapper">
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 info-container about-container">
			<img class="ttt-icon" width="53" height="53" src="{{Config::get('app.url')}}/images/global/ttt-icon-outline.png">
			<h1>Two Thousand Times is now Sondry</h1>
		</div>
	</div>
</div>
</div>
	
	
@stop
