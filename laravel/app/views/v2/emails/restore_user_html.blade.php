@extends('v2.emails.email_layout')

@section('content')
	<h1>Welcome back to Sondry!</h1>
	<p>{{$user->username}}, welecome back!</p>
	<p>You can restore your account by clicking below:</p>
	<a href="{{Config::get('app.secureurl')}}/user/restore/{{$user->restore_confirm}}">Restore my account</a>

@stop