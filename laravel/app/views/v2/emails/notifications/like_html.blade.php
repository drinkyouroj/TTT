@extends('v2.emails.email_layout')

@section('content')

{{$post->user->username}},

{{$user->username}} has liked “{{$post->title}}” To view {{$user->username}}’s profile, click <a href="{{ URL::to('profile/'.$user->username ) }}">here</a>. 

You can edit your email notifications in account settings.


@stop