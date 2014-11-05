@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('title')
	What's Sondry? | Sondry
@stop

@section('content')
<div class="about-wrapper">
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 info-container about-container namechange">
			<img class="ttt-icon" src="{{Config::get('app.url')}}/images/global/gold-icon.png">
			<h1>What's Sondry?</h1>

			<p class="bold">Hey Everyone,</p>
 
			<p>Sondry originates from Middle English, and is often used to refer to sondry folk, meaning a diverse group of people. Which is exactly what we have here.</p>
			 
			<p>After receiving feedback from many of our incredible storymakers, this change is being made to better define the platform you’ve helped grow, and grow it has. 250,000 people have viewed the stories you made in under two months. But we’re just getting started. Sondry both signifies and accompanies the increased expansion of our community. With your help, we are growing exponentially. So thank you. Please continue to share our site with storymakers all over the world.</p>
			 
			<p>As always, feel free to email us directly at team@sondry.com.</p>
			 
			<p>Thanks again. Let’s change the world.</p>
			<p class="bold signoff">Team</p>
			<img class="sign-logo" src="{{ URL::to('images/global/sondry-logo-min.png') }}" alt="SONDRY">
		</div>
	</div>
</div>
</div>
	
	
@stop
