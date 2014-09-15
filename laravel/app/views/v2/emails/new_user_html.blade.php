@extends('v2.emails.email_layout')

@section('content')
	<h1>Thanks for joining Two Thousand Times!</h1>
	<p>{{$data['username']}}, enjoy our site!</p>
	<a href="{{Config::get('app.url')}}/user/confirm/{{$data['confirm']}}">
		Confirm your account
	</a>
@stop