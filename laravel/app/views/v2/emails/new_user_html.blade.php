@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Welcome {{$user->username}},</h1>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Your TTT account has been activated. Please click the link below to verify your account. Enjoy!</p>

	<a href="{{Config::get('app.secureurl')}}/user/confirm/{{$user->confirmation_code}}" target="_blank" style="color:#32b1c6; text-decoration:none; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:14px;">Verify your account</a>

	<p class="signature" style="color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:18px;">Team</p>

	<img style="margin-bottom:50px;" alt="Sondry" class="header-logo" width="150" height="28" src="{{Config::get('app.staticurl')}}/images/email/email-logo-2.gif">
@stop