@extends('v2.emails.email_layout')

@section('content')
	<h1>Hey {{$user->username}}, please confirm the updated email.</h1>
	<p>Updated Email: {{$user->updated_email}}</p>
	<p>
		<a href="{{Config::get('app.url')}}/user/emailupdate/{{$user->update_confirm}}">Confirm</a>
	</p>
	<a href="http://twothousandtimes.com">Log Back in</a>

@stop