@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Hi {{$user->username}},</h1>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Your email has been updated.</p>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Updated Email: <span style="color:#000000; text-decoration:none; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:14px;">{{$user->updated_email}}</span></p>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">To verify your account or update your email address please visit the link below:</p>

	<a href="{{Config::get('app.secureurl')}}/user/emailupdate/{{$user->update_confirm}}" target="_blank" style="color:#32b1c6; text-decoration:none; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:14px;">Email Update</a>

	<p class="signature" style="margin-top:30px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:18px;">Sondry</p>

	<img style="margin-bottom:50px;" alt="Sondry" class="header-logo" width="150" height="28" src="http:{{Config::get('app.staticurl')}}/images/email/email-logo-2.gif">

@stop