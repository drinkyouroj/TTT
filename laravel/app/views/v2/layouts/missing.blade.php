@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css">
@stop

@section('title')
	404 - Can't Find it! | The Twothousand Times
@stop

@section('content')

<div class="col-md-6 col-md-offset-3 info-container">
	<h2>We can't find what you are looking for</h2>
	<p>Hey, maybe the link wasn't quite right.</p>
</div>
	
	
@stop
