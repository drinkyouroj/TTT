@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('title')
	Welcome to our Early Access Program
@stop

@section('title')
	Beta | The Twothousand Times
@stop

@section('content')

<div class="col-md-6 col-md-offset-3 info-container">
	<h2 style="text-align: center;">Welcome to the Early Access</h2>
	
</div>
	
	
@stop
