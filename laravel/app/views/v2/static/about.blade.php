@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css">
@stop

@section('title')
	About
@stop

@section('title')
	About | The Twothousand Times
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-3 info-container">
		<h2>About</h2>
			<p>A couple years ago, we got fed up with the daily grind of social media and “went off the grid,” so to speak. After leaving these networks of instant accessibility behind, we found ourselves more comfortable talking openly with strangers. They were willing to share and we were ready to listen.</p>
			<p>These revealing and genuine conversations garnered retellable stories, introspective thoughts, and life-guiding advice from individuals we’d likely never hear from again. The only trouble: we wanted to hear more, but had no control over when these exchanges would occur.</p>
			<p>With this in mind, we sought out to make these exchanges more accessible, which resulted in The Two Thousand Times – an online shoebox for compelling stories, thoughts, and advice that are simply inaccessible on a day to day basis.</p>
			<p>The Two Thousand Times offers a safe online environment to compile, share, view, and discuss these authentic aspects of your own life and the lives of others. Instead of expanding personal “online brands,” our focus is rather on saving and sharing content that you’ll find valuable. </p>
			</br>
			</br>
			<p><span>There’s only one rule:</span> all submissions must be two thousand words or less. </p>
			<p>Feel free to take a look around, read a post or two, and if your interest is piqued, we would love to welcome you into our community. There are no subscriptions or fees. There never will be. </p>
			<p>Your privacy is our priority. We will never ask for any personal information that could link your account to your identity. You don’t even have to use your email to sign up – it’s completely up to you. Our features have been developed with your security in mind.  For example, users are unable to upload their own photos to their submissions, as digital photos may carry information about who took them or where they were taken.</p>
			<p>If you have any reservations about our dedication to user security, I encourage you to visit our <a href="{{Config::get('app.url')}}/etiquette">Community Guidelines.</a></p>
		</div>
	</div>
</div>
	
	
@stop
