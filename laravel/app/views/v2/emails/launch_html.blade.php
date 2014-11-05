@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Hi {{$user->username}},</h1>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">This is your final reminder to activate your Sondry account before it goes back up for grabs in <span style="font-weight:bold;">24 hours</span>. Please visit the link below and enter your password to activate your account.</p>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Enjoy!</p>

	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">password: <span style="font-weight:bold;">{{$pass}}</span></p>

	<a href="https://sondry.com/user/login" target="_blank" style="color:#32b1c6; text-decoration:none; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:14px;">sondry.com/user/login</a>

	<p class="signature" style="color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:18px;">Team</p>

	<img style="margin-bottom:50px;" alt="Sondry" class="header-logo" width="150" height="28" src="http:{{Config::get('app.staticurl')}}/images/email/email-logo-2.gif">
@stop