@extends('v2.emails.email_layout')

@section('content')
	<h1>Thanks for joining Two Thousand Times!</h1>
	<p>{{$user->username}}, enjoy all the writing and stuff</p>
	<p>Here's your new pass: {{$pass}}</p>
	<a href="http://twothousandtimes.com">Check it out</a>

@stop