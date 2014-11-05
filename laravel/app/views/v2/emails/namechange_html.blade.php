@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Hey Everyone,</h1>
	 
	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Sondry originates from Middle English, and is often used to refer to sondry folk, meaning a diverse group of people. Which is exactly what we have here.</p>
	 
	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">After receiving feedback from many of our incredible storymakers, this change is being made to better define the platform you’ve helped grow, and grow it has. 250,000 people have viewed the stories you made in under two months. But we’re just getting started. Sondry both signifies and accompanies the increased expansion of our community. With your help, we are growing exponentially. So thank you. Please continue to share our site with storymakers all over the world.</p>
	 
	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">As always, feel free to email us directly at <a href="mailto:team@sondry.com" target="_blank" style="color:#32b1c6; text-decoration:none; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:14px;">team@sondry.com.</a></p>
	 
	<p style="color:#000000; font-family: Baskerville,Baskerville Old Face,Hoefler Text, Garamond,Times New Roman,Gerogia,serif; font-weight:normal; font-size:16px;">Thanks again. Let’s change the world.</p>
	<p class="signature" style="margin-bottom:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:18px;">Team</p>
	<img class="header-logo" width="150" height="28" src="{{Config::get('app.url')}}/images/email/email-logo-2.gif">
@stop