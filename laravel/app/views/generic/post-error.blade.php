@extends('layouts.master')
@section('content')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/errors.css" rel="stylesheet" media="screen">
@stop

<div class="col-md-8 col-md-offset-2 post-error">
	<h2>You’ve reached this page because you’ve posted in the past ten minutes.</h2>
</div>
@stop
