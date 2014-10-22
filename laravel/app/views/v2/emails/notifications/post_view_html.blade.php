@extends('v2.emails.email_layout')

@section('content')

{{$post->user->username}},

Your post, “{{$post->title}}” has reached over {{$views}} views. To view your post click <a href="{{URL::to('posts/'.$post->alias)}}">here</a>.

You can edit your email notifications in account settings.

@stop