@extends('v2.emails.email_layout')

@section('content')

<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">{{$post->user->username}},</h1>

<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Your post, “{{$post->title}}” has reached over {{$views}} views.</p>

<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">To view your post click <a href="{{URL::to('posts/'.$post->alias)}}" style="color:#31b0c5;">here</a>.</p>

<img class="header-logo" style="margin-top:30px;" alt="Sondry" width="150" height="28" src="http:{{Config::get('app.staticurl')}}/images/email/email-logo-2.gif">

<p style="margin-bottom:50px; color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:13px;">You can edit your email notifications in account settings.</p>
@stop