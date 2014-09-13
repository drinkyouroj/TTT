@extends('v2.emails.email_layout')

@section('content')
	<h1>Hey {{$user->username}}, we all forget things.</h1>
	<p>Here's your new password: {{$new_pass}}</p>
	<a href="http://twothousandtimes.com">Log Back in</a>

@stop