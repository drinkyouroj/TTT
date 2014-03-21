@extends('layouts.master')


@section('css')
	<link href="{{Config::get('app.url')}}/css/views/undelete.css" rel="stylesheet" media="screen">
@stop

@section('title')
Welcome Back {{$user->username}}!
@stop

@section('content')
	<div class="col-md-4 col-md-offset-4 undel-container">
		<h2>Welcome Back {{$user->username}}!</h2>
		<p>Are you ready to rejoin our motley crew?</p>
		<a class="btn btn-primary" href="{{Config::get('app.url')}}/user/restore/{{$user->id}}/{{$restore_string}}">Yes!</a>
		<a class="btn btn-primary" href="{{Config::get('app.url')}}/user/logout">No! Get me out of here!</a>
	</div>
@stop