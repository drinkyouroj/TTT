@extends('v2.emails.email_layout')

@section('content')
	<h1>Thanks for joining Two Thousand Times!</h1>
	<p>{{$data['username']}}, enjoy all the writing and stuff</p>

@stop